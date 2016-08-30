<?php

/**
 * Leviu
 *
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Leviu\Mvc;

/**
 * view
 * 
 */
class View implements \SplObserver
{
    
    /**
     * 
     * @var array $data Data for the dynamic view
     */
    protected $data = array();

    /**
     *
     * @var object Template utilized for data
     */
    protected $template;
    
    /**
     *
     * @var object Model for access data
     */
    protected $model;
    

    /**
     * Constructor.
     * 
     */
    public function __construct($model)
    {
        $this->model = $model;
    }
    
    /**
     * Render a template
     */
    public function render()
    {
        $this->template->data = (object) $this->data;
        $this->template->output();
    }
    
    /**
     * Update observer data  
     * 
     * @param \SplSubject $subject
     */
    public function update(\SplSubject $subject)
    {
        if ($subject instanceof Model) {
            $this->data = array_merge($this->data, $subject->getUpdate);
        }
    }   
}
