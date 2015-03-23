<?php namespace JohnGerome\Sm\Components;

use Cms\Classes\ComponentBase;
use JohnGerome\Sm\Models\Project;
use JohnGerome\Sm\Models\Contact;

class Subscriber extends ComponentBase
{

  public function componentDetails() {
    return [
      'name'        => 'johngerome.sm::lang.subscribe.com_name',
      'description' => 'johngerome.sm::lang.subscribe.com_description'
    ];
  }

  public function defineProperties() {
    return [
      'Project' => [
            'title'     => 'johngerome.sm::lang.projects.project_name',
            'type'      => 'dropdown',
            'required'  => true,
            'options'   => $this->getProjectOptions()
        ]
    ];
  }

  public function onRun()
  {
     $this->page['project'] = $this->property('Project');
     $this->addJs('/plugins/johngerome/sm/assets/js/geo.js');
  }

  public function getProjectOptions() {
     return Project::lists('name', 'id');
  }

  public function onAddSubscriber() {
    $error = false;
    $message = 'Thank You for Subscribing';

    $project_id = post('project');
    $data = [
        "email"        => post('email'),
        "latitude"     => post('latitude'),
        "longitude"    => post('longitude'),
    ];

    try{

        if(!Project::find($project_id)) {
          $error = true;
          $message = 'Project Not Found!';
        }
        else {
          $contact = Contact::create($data);
          $contact->projects()->attach($project_id);
        }

        $this->page['result'] = $message;
    }
    catch (\Exception $e){
        $this->page['error'] = $error;
        $this->page['result'] = $e->getMessage();
    }
  }
}