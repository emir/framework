<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Session\MemcachedSessionHandler;
use Linna\Session\Session;
use PHPUnit\Framework\TestCase;

/**
 * Memcached Session Handler Test
 */
class MemcachedSessionHandlerTest extends TestCase
{
    /**
     * @var Session The session class.
     */
    protected $session;

    /**
     * @var MemcachedSessionHandler The session handler class.
     */
    protected $handler;

    /**
     * @var Memcached The memcached class.
     */
    protected $memcached;

    /**
     * Setup.
     */
    public function setUp()
    {
        if (!class_exists('Memcached')) {
            return;
        }

        $memcached = new Memcached();
        
        $memcached->addServer($GLOBALS['mem_host'], (int) $GLOBALS['mem_port']);

        $handler = new MemcachedSessionHandler($memcached, 5);
        $session = new Session(['expire' => 10]);
        
        $session->setSessionHandler($handler);
        
        $this->handler = $handler;

        $this->session = $session;
    }

    /**
     * Test Session Start.
     *
     * @runInSeparateProcess
     */
    public function testSessionStart()
    {
        $session = $this->session;

        $this->assertEquals(1, $session->status);

        $session->start();

        $this->assertEquals(2, $session->status);

        $session->destroy();
    }
    
    /**
     * Test session commit.
     *
     * @runInSeparateProcess
     */
    public function testSessionCommit()
    {
        $session = $this->session;
        $session->start();
        
        $this->assertEquals($session->id, session_id());
        
        $session['fooData'] = 'fooData';
        
        $session->commit();
        
        $session->start();
        
        $this->assertEquals($session->id, session_id());
        $this->assertEquals('fooData', $session['fooData']);
        
        $session->destroy();
    }
    
    /**
     * Test session destroy.
     *
     * @runInSeparateProcess
     */
    public function testSessionDestroy()
    {
        $session = $this->session;

        $session->start();
        $session['fooData'] = 'fooData';
        
        $this->assertEquals(2, $session->status);
        $this->assertEquals(session_id(), $session->id);
        $this->assertEquals('fooData', $session['fooData']);
        
        $session->destroy();
        
        $this->assertEquals(1, $session->status);
        $this->assertEquals('', $session->id);
        $this->assertFalse($session['fooData']);
    }
    
    /**
     * Test session regenerate.
     *
     * @runInSeparateProcess
     */
    public function testSessionRegenerate()
    {
        $session = $this->session;

        $session->start();
        $session['fooData'] = 'fooData';
        
        $sessionIdBefore = session_id();
        
        $this->assertEquals(2, $session->status);
        $this->assertEquals($sessionIdBefore, $session->id);
        $this->assertEquals('fooData', $session['fooData']);
        
        $session->regenerate();
        
        $sessionIdAfter = session_id();
        
        $this->assertEquals(2, $session->status);
        $this->assertEquals($sessionIdAfter, $session->id);
        $this->assertNotEquals($sessionIdAfter, $sessionIdBefore);
        $this->assertEquals('fooData', $session['fooData']);
        
        $session->destroy();
    }
    
    /**
     * Test session expired.
     *
     * @runInSeparateProcess
     */
    public function testSessionExpired()
    {
        $session = $this->session;

        $session->start();

        $session_id = $session->id;

        $session->time = $session->time - 1800;

        $session->commit();

        $session->setSessionHandler($this->handler);

        $session->start();

        $session2_id = $session->id;

        $this->assertNotEquals($session_id, $session2_id);
        $this->assertEquals(2, $session->status);

        $session->destroy();
    }

    /**
     * Test garbage.
     *
     * @runInSeparateProcess
     */
    public function testGc()
    {
        if (!class_exists('Memcached')) {
            $this->markTestSkipped('Memcached module not installed');
        }

        $this->assertTrue($this->handler->gc(0));
    }
}
