<?php

namespace Controller;

use Model\File as FileModel;
/**
 * Import controller
 *
 * @package  controller
 * @author   Walid Miled
 */
class Import extends Base
{
    /**
     * Common Import method
     *
     * @access private
     */
    private function common($action, $page_title)
    {
		$project = $this->getProject();
        $from = $this->request->getStringParam('from');
        $to = $this->request->getStringParam('to');
		
        $this->response->html($this->projectLayout('import/'.$action, array(
            'values' => array(
                'controller' => 'import',
                'action' => $action,
                'project_id' => $project['id'],
                'from' => $from,
                'to' => $to,
				            ),
            'errors' => array(),
            'date_format' => $this->config->get('application_date_format'),
            'date_formats' => $this->dateParser->getAvailableFormats(),
            'project' => $project,
            'title' => $page_title,
        ))); 
    }
    /**
     * Task Import
     *
     * @access public
     */
    public function tasks()
    {
       $this->common('tasks', t('Tasks Import'));
    }
	
	 /**
     * Read and process CSV  files
     *
     * @access public
     */
	public function csvRead()
	{	 
		$csvFile 	= $this->file->uploadTasks($project,'files'); 
		$project 	= $this->getProject();
		$column		= $_POST['columnId'];
		$swimlane	= $_POST['swimId'];
		$errors		= false;
	
		if (count($csvFile) === 1) 
		{
			$row = 1;
			if (($handle = fopen($csvFile[0], "r")) !== FALSE) {
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$row++;
					if ($data[0] != null)
					{
						$title 			= $data[0];
					}
					else
					{
						$title = "";
					}
					
					if ($data[1] != null)
					{
						$description 	= $data[1];
					}
					else
					{
						$description = "";
					}		
					$values = array("title"=>$title, "description"=>$description, "project_id"=>$project['id'], "swimlane_id"=>$swimlane, "owner_id"=>"", "category_id"=>"", "column_id"=>$column, "color_id"=>"yellow", "score"=>"", "time_estimated"=>"", "date_due"=>"");
					$values['creator_id'] = $this->userSession->getId();
					list($valid, $errors) = $this->taskValidator->validateCreation($values);

					if ($valid) {

						if ($this->taskCreation->create($values)) {
							$this->session->flash(t('Task created successfully.'));
							unset($values['title']);
							unset($values['description']);
						}
					}
					else
					{
						$errors = true;
					}
				}
				fclose($handle);
			}		
			if (!$errors)
			{
				$this->session->flash(t('Tasks imported successfully.'));
			}
			else
			{
				$this->session->flash(t('Some Tasks were not imported.'));
			}
				$this->response->redirect('?controller=board&action=show&project_id='.$project['id']);
		}
        else {
            $this->session->flashError(t('Unable to upload the file.'));
            $this->response->redirect('?controller=Import&action=tasks&project_id='.$project['id']);
        }
	}
}
