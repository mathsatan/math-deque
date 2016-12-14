<?php
/*if (!defined('MODEL_ARTICLES_PHP')){
    if (!defined('MODEL_ARTICLES_PHP')) include 'app/models/modelarticles.php';
}*/

class ControllerError extends Controller
{
    private $msg;
    public function __construct($errorMsg)
    {
        parent::__construct();
        $this->msg = $errorMsg;
    }

    public function action_index()
	{
        $data['error_msg'] = $this->msg;
        $this->view->generate('errorview.php', 'errortemplateview.php', $data);
	}
}
