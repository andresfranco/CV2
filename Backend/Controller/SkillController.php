<?php
class SkillController{
	private $database;
	private $editurl;
	private $deleteurl;
	private $mainlink;
	private $mainoption;
	public function __construct($app,$medoo,$globalobj) {
		$this->database =$medoo;
		$this->app=$app;
		$this->globalobj =$globalobj;
		$this->editurl ='skill';
		$this->deleteurl='/skill/delete/';
		$this->mainlink = '/skills';
		$this->mainoption ='Skill';
		$this->deleteViewPath ='Views/Skill/skilldelete.html.twig';
		$this->skillFormPath ='Views/Skill/skillform.html.twig';
		$this->gridViewPath ='Views/Skill/skills.html.twig';
		$this->tableName ='skill';
		$this->currentDate= date('Y-m-d H:i:s');
	}
	
	
	function getSkillRoutes(){
		$routes =['basepath'=>$this->globalobj->getsysparam('mainurl'),'editurl'=>$this->editurl,'deleteurl'=>$this->deleteurl,'mainlink'=>$this->mainlink];
		return $routes;
	}
	
	function rendergridview(){
		$this->app->render($this->gridViewPath,
		[  'newurl'=>$this->app->urlFor('skillForm')
				,'editurl'=>$this->editurl
				,'deleteurl'=>$this->deleteurl
				,'obj'=>$this
				,'globalobj'=>$this->globalobj
				,'option'=>$this->mainoption
				,'route'=>''
				,'gridData'=>$this->getall()
				,'link'=>$this->mainlink
		]);
	}
	
	
	// Render New and Edit Forms 
	function renderSkillForm($skillForm){
		
		$skillForm['id'] >0?($skillForm = $this->getskillbyid($skillForm['id'])[0]  AND $formUrl = str_replace(':id',$skillForm['id'], $this->app->urlFor('updateskill')) AND $route ='Edit')
			                 :($route ='New' AND $formUrl =$this->app->urlFor('skillForm'));
    
		$this->app->render($this->skillFormPath,
		[ 'skillForm'=>$skillForm																 
			,'globalobj'=>$this->globalobj
			,'formUrl'=>$formUrl
			,'listurl'=>$this->app->urlFor('skills')
			,'option'=>$this->mainoption
			,'route'=>$route
			,'link'=>$this->mainlink
		]);
	}	

	
	//Validate existent skill
	function validateinsert($curricullumid,$type,$skill){
		$count =$this->findskill($curricullumid,$type,$skill);
		$count >0 ? $errormessage = '<div class="alert alert-danger col-sms-4 errordiv" role="alert"><i class="fa fa-warning"></i>The skill for this curricullum and this type already exist</div>':$errormessage="";
		return $errormessage;
	}

  //Insert new Skill
	function addnewitem($skillFields){
		$errormessage = $this->validateinsert($skillFields['curricullumid'],$skillFields['type'],$skillFields['skill']);

		if($errormessage==""){   
			
			//Set Audit Fields
			isset($skillFields['active'])?$active=$skillFields['active']:$active="";
			$skillFields['active']=$this->globalobj->validateChecked($active);
			$skillFields['createuser']=$this->globalobj->getcurrentuser();
			$skillFields['createdate']=$this->currentDate;
			$skillFields['modifyuser']=$this->globalobj->getcurrentuser();
			$skillFields['modifydate']=$this->currentDate;
			
		  $this->database->insert($this->tableName,$skillFields);
			$this->app->response->redirect($this->app->urlFor('skills'), ['newurl'=>$this->app->urlFor('skillForm') ,'editurl'=>$this->editurl,'deleteurl'=>$this->deleteurl]);
		}
		else
		{
			$route ='New';
			$skillFields['errormessage'] =$errormessage;
			$formUrl =$this->app->urlFor('skillForm');
			$this->app->render($this->skillFormPath,
				['skillForm'=>$skillFields																 
					,'globalobj'=>$this->globalobj
					,'formUrl'=>$formUrl
					,'listurl'=>$this->app->urlFor('skills')
					,'option'=>$this->mainoption
				  ,'route'=>$route
				  ,'link'=>$this->mainlink
				]);
		}


	}
   
	 //Update Skill
  function updateitem($id,$skillFields)
    { 
		  //Set Audit Fields 
			isset($skillFields['active'])?$active=$skillFields['active']:$active="";
			$skillFields['active']=$this->globalobj->validateChecked($active);
			$skillFields['modifyuser']=$this->globalobj->getcurrentuser();
			$skillFields['modifydate']=$this->currentDate;

			$this->database->update($this->tableName,$skillFields, ["id[=]" =>$id]);
			$this->app->response->redirect($this->app->urlFor('skills'),['newurl' => $this->app->urlFor('skillForm'),'editurl' => $this->editurl,'deleteurl' => $this->deleteurl]);
    }

	//Delete Skill
	
	function deleteitem($id)
	{
		$this->database->delete($this->tableName,["AND" => ["id" => $id]]);
		$this->app->response->redirect($this->app->urlFor('skills'),['newurl' => $this->app->urlFor('skillForm'),'editurl' => $this->editurl,'deleteurl' => $this->deleteurl]);
	}
	
	//Get grid Values
	function getall(){
		$sth = $this->database->pdo->prepare(
		"select sk.id ,sk.curricullumid,sk.type,sk.skill,sk.percentage,sk.level
		,(case sk.active  when 1 then 'Yes'  when 0 then 'No' end) as active
		,c.name as cvname from skill sk inner join curricullum c on (sk.curricullumid = c.id) ");
		$sth->execute();
		return $sth;

	}
	
	//Skill by Id
	function getskillbyid($id){
		$data = $this->database->select("skill",["id" ,"curricullumid","type","skill","percentage","level","active","description"	],["id" => $id]);
    return $data;   
	}

	//Check if exists a skill with the same type and curricullumid
	function findskill($curricullumid,$type,$skill){
		$count =  $this->database->count($this->tableName,["id"],["AND" =>["curricullumid" => $curricullumid,"type"=>$type,"skill"=>$skill]]);
		return $count;   
	}

}
