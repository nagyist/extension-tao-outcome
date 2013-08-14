<?php

require_once dirname(__FILE__) . '/../../tao/test/TaoTestRunner.php';
include_once dirname(__FILE__) . '/../includes/raw_start.php';
/**
 * TODO Not a real unit test, script used for the dev. time , will be removed
 */
class DbResultsServerTestCase extends UnitTestCase {
	 /**
	  *
	  * @var taoResultServer_models_classes_ResultsServer
	  */
	private $resultServer;

	public function setUp(){		
		TaoTestRunner::initTest();
		

	}


    private function spawnDependantData() {
        $subjectClass= new core_kernel_classes_Class(TAO_SUBJECT_CLASS);
        $testTaker = $subjectClass->createInstanceWithProperties(array(
					RDFS_LABEL					=> "tempTTforResultsTest".rand(0,65535),
                    PROPERTY_USER_LOGIN	=> time().rand(0,65535),
                    PROPERTY_USER_FIRSTNAME	=> "randTest".rand(0,65535),
                    PROPERTY_USER_LASTNAME => "randTest".rand(0,65535),
                    PROPERTY_USER_MAIL => "foo@foo.bar",
				));
        $testClass= new core_kernel_classes_Class(TAO_TEST_CLASS);
        $test = $testClass->createInstanceWithProperties(array(
					RDFS_LABEL					=> "tempTestforResultsTest".rand(0,65535),
				));
        $itemClass= new core_kernel_classes_Class(TAO_ITEM_CLASS);
        $item = $itemClass->createInstanceWithProperties(array(
					RDFS_LABEL					=> "tempItemforResultsTest".rand(0,65535),
                    TAO_ITEM_MODEL_PROPERTY => TAO_ITEM_MODEL_XHTML
				));
        $deliveryClass= new core_kernel_classes_Class(TAO_DELIVERY_CLASS);
        $delivery = $deliveryClass->createInstanceWithProperties(array(
					RDFS_LABEL					=> "tempDeliveryforResultsTest".rand(0,65535),
				));
        return array($testTaker->getUri(),$test->getUri(),$item->getUri(), $delivery->getUri());
    }
    public function testSetResult() {
        $resultIdentifier = "atrial".rand(0,512);
        $tempData = $this->spawnDependantData();
        $testTaker = $tempData[0];
        $delivery = $tempData[3];
        $test = $tempData[1];
        $item = $tempData[2];
        
        $this->resultServer = new taoResultServer_models_classes_DbResultServer($resultIdentifier);
        $this->assertIsA($this->resultServer, 'taoResultServer_models_classes_DbResultServer');
        $this->resultServer->storeTestTaker($testTaker );
        $this->resultServer->storeDelivery($delivery);
         $outComeVariable = new taoResultServer_models_classes_OutcomeVariable();
        $outComeVariable->setBaseType("int");
        $outComeVariable->setCardinality("single");
        $outComeVariable->setIdentifier("Spatial representation");
        $outComeVariable->setValue("".rand(0,30));
        $this->resultServer->storeItemVariable($test, $item, $outComeVariable, "xxx");

        $outComeVariable = new taoResultServer_models_classes_OutcomeVariable();
        $outComeVariable->setBaseType("int");
        $outComeVariable->setCardinality("single");
        $outComeVariable->setIdentifier("Rotation in Space");
        $outComeVariable->setValue("".rand(0,50));
        $this->resultServer->storeItemVariable($test, $item, $outComeVariable, "yyy");

        $responseVariable = new taoResultServer_models_classes_ResponseVariable();
        $responseVariable->setBaseType("int");
        $responseVariable->setCardinality("single");
        $responseVariable->setIdentifier("historyResponse");
        $responseVariable->setCandidateResponse("choice_".rand(0,5));
        $responseVariable->setCorrectResponse(true);
        $this->resultServer->storeItemVariable($test, $item, $responseVariable, "yyy");

        //an unscored response
         $responseVariable = new taoResultServer_models_classes_ResponseVariable();
        $responseVariable->setBaseType("int");
        $responseVariable->setCardinality("single");
        $responseVariable->setIdentifier("Dissertation");
        $responseVariable->setCandidateResponse("La raison du plus fort est toujours la meilleure :
Nous l'allons montrer tout à l'heure.
Un Agneau se désaltérait
Dans le courant d'une onde pure.
Un Loup survient à jeun qui cherchait aventure,
Et que la faim en ces lieux attirait.
Qui te rend si hardi de troubler mon breuvage ?
Dit cet animal plein de rage :
Tu seras châtié de ta témérité.");
        $responseVariable->setCorrectResponse(true);
        $this->resultServer->storeItemVariable($test, $item, $responseVariable, "yyy");


        $responseVariable = new taoResultServer_models_classes_ResponseVariable();
        $responseVariable->setBaseType("int");
        $responseVariable->setCardinality("single");
        $responseVariable->setIdentifier("populationResponse");
        $responseVariable->setCandidateResponse("choice_".rand(0,5));
        $responseVariable->setCorrectResponse(false);
        $this->resultServer->storeItemVariable($test, $item, $responseVariable, "yyy");

        //4 different observations are submitted for the sam variableIdentifier
         $responseVariable = new taoResultServer_models_classes_ResponseVariable();
        $responseVariable->setBaseType("int");
        $responseVariable->setCardinality("single");
        $responseVariable->setIdentifier("planets");
        $responseVariable->setCandidateResponse("choice_1");
        $responseVariable->setCorrectResponse(false);
        $this->resultServer->storeItemVariable($test, $item, $responseVariable, "yyy");

        $responseVariable = new taoResultServer_models_classes_ResponseVariable();
        $responseVariable->setBaseType("int");
        $responseVariable->setCardinality("single");
        $responseVariable->setIdentifier("planets");
        $responseVariable->setCandidateResponse("choice_3");
        $responseVariable->setCorrectResponse(false);
        $this->resultServer->storeItemVariable($test, $item, $responseVariable, "yyy");

        $responseVariable = new taoResultServer_models_classes_ResponseVariable();
        $responseVariable->setBaseType("int");
        $responseVariable->setCardinality("single");
        $responseVariable->setIdentifier("planets");
        $responseVariable->setCandidateResponse("choice_5");
        $responseVariable->setCorrectResponse(true);
        $this->resultServer->storeItemVariable($test, $item, $responseVariable, "yyy");

        $responseVariable = new taoResultServer_models_classes_ResponseVariable();
        $responseVariable->setBaseType("int");
        $responseVariable->setCardinality("single");
        $responseVariable->setIdentifier("planets");
        $responseVariable->setCandidateResponse("choice_4");
        $responseVariable->setCorrectResponse(false);
        $this->resultServer->storeItemVariable($test, $item, $responseVariable, "yyy");



        //trace variable
        $traceVariable = new taoResultServer_models_classes_TraceVariable();
        $traceVariable->setBaseType("string");
        $traceVariable->setCardinality("single");
        $traceVariable->setIdentifier("myTrace");

        $traceVariable->setTrace("<xml><event id='5' domNode='xpath:/body/thiselement' eventName= 'Click' value=''</xml> ");
        $this->resultServer->storeItemVariable($test, $item, $traceVariable, "yyy");

    }
/*
       public function testSetPoorlySpecifiedResult() {
        $resultIdentifier = "loose".rand(0,512);
        $testTaker = 'tt';
        $delivery = 'del';
        $test = 'test';
        $item = "item";

        $this->resultServer = new taoResultServer_models_classes_DbResultServer($resultIdentifier);
        $this->assertIsA($this->resultServer, 'taoResultServer_models_classes_DbResultServer');
        $this->resultServer->storeTestTaker($testTaker );
        $this->resultServer->storeDelivery($delivery);
         $outComeVariable = new taoResultServer_models_classes_OutcomeVariable();
        $outComeVariable->setBaseType("int");
        $outComeVariable->setCardinality("single");
        $outComeVariable->setIdentifier("Spatial representation");
        $outComeVariable->setValue("".rand(0,30));
        $this->resultServer->storeItemVariable($test, $item, $outComeVariable, "xxx");

        $outComeVariable = new taoResultServer_models_classes_OutcomeVariable();
        $outComeVariable->setBaseType("int");
        $outComeVariable->setCardinality("single");
        $outComeVariable->setIdentifier("Rotation in Space");
        $outComeVariable->setValue("".rand(0,50));
        $this->resultServer->storeItemVariable($test, $item, $outComeVariable, "yyy");

        $responseVariable = new taoResultServer_models_classes_ResponseVariable();
        $responseVariable->setBaseType("int");
        $responseVariable->setCardinality("single");
        $responseVariable->setIdentifier("historyResponse");
        $responseVariable->setCandidateResponse("choice_".rand(0,5));
        $responseVariable->setCorrectResponse(true);
        $this->resultServer->storeItemVariable($test, $item, $responseVariable, "yyy");

        //an unscored response
         $responseVariable = new taoResultServer_models_classes_ResponseVariable();
        $responseVariable->setBaseType("int");
        $responseVariable->setCardinality("single");
        $responseVariable->setIdentifier("Dissertation");
        $responseVariable->setCandidateResponse("²&é'(-è_çà');");
        $responseVariable->setCorrectResponse(true);
        $this->resultServer->storeItemVariable($test, $item, $responseVariable, "yyy");


        $responseVariable = new taoResultServer_models_classes_ResponseVariable();
        $responseVariable->setBaseType("int");
        $responseVariable->setCardinality("single");
        $responseVariable->setIdentifier("nocoorectresponse");
        $responseVariable->setCandidateResponse("choice_".rand(0,5));

        $this->resultServer->storeItemVariable($test, $item, $responseVariable, "yyy");


    }
*/
}
?>