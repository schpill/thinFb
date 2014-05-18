<?php
    namespace Thin;
    class staticController extends Controller
    {
        public function init()
        {

        }

        public function preDispatch()
        {

        }

        public function homeAction()
        {
            $this->view->title = 'home';
        }

        public function testAction()
        {

        }

        public function postDispatch()
        {

        }

        public function quit()
        {

        }
    }
