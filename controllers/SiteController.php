<?php

namespace app\controllers;

use app\models\Accounts;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\FormsHeader;
use app\models\Questions;
use app\models\Answers;
use app\models\Variants;
use Symfony\Component\Console\Question\Question;
use  yii\db\Query;

class SiteController extends Controller
{
    public $layout;
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->redirect(['site/question']);
    }
    public function actionResponse()
    {
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->isAjax) {
                if(isset($_POST['actionName'])){
                    return $this->redirect(['site/'.$_POST['actionName']]);
                }
                else if(isset($_POST['findFormDetails']) && isset($_POST['formid'])){
                    $returnArray = [];
                    $questionobj = new Questions;
                    $questionarray = $questionobj->getAllQuestionsChart($_POST['formid']);
                    $answerobj = new Answers();
                    $variantsobj = new Variants;

                    $allusercounts =  $variantsobj->getVariantsUserCount($_POST['formid']);
                    $newArray = [];
                    for($i = 0 ; $i < count($questionarray);$i++){
                       
                        $answerarray = $answerobj->getAllAnswers($questionarray[$i]['id']);


                        for($b = 0 ; $b < count($answerarray) ; $b++){
                            $variantcount = $variantsobj->getVariantsCount($answerarray[$b]['id']);
                            $answerarray[$b]['count'] = $variantcount;
                        }



                        $questionarray[$i]['answers']= $answerarray;
                        $questionarray[$i]['users']= $allusercounts;
                    }




                    // $obj = (object) array(
                    //     'questions' => $questionarray
                    // );








                    // array_push($returnArray,$obj);

                    
                    return json_encode($questionarray);
                }
                else if(isset($_POST['findResponses']) && isset($_POST['dateFrom']) && isset($_POST['dateTo'])){

                    //return 'anop';
                    $formHeader = new FormsHeader;
                    $resposnesArray = $formHeader->getFormsByDate($_POST['dateFrom'],$_POST['dateTo']);
                    $stringToShow = '<table id="resposesTable">';
                    $stringToShow .= '<thead>';
                    $stringToShow .= '<tr>';
                    $stringToShow .= '<th>';
                    $stringToShow .= 'ID';
                    $stringToShow .= '</th>';
                    $stringToShow .= '<th>';
                    $stringToShow .= 'Form Name';
                    $stringToShow .= '</th>';
                    $stringToShow .= '<th>';
                    $stringToShow .= 'Creation Date';
                    $stringToShow .= '</th>';
                    $stringToShow .= '</tr>';
                    $stringToShow .= '</thead>';
                    $stringToShow .= '<tbody>';
                    for($i = 0 ; $i < count($resposnesArray) ; $i++){
                        $stringToShow .='<tr class="responseformRows" formid="'.$resposnesArray[$i]['id'].'">';
                        $stringToShow .='<td>'.$resposnesArray[$i]['id'].'</td><td>'.$resposnesArray[$i]['name'].'</td><td>'.$resposnesArray[$i]['creation_date'].'</td>';
                        $stringToShow .='</tr>';
                    }
                    $stringToShow .= '</tbody>';
                    $stringToShow .= '</table>';

                    return $stringToShow ;
                }
                else if(isset($_POST['findResponsesByForm']) && isset($_POST['formid'])){
                    $variants = new Variants();
                    $resposnessArray = $variants->getVariantsFullDetails($_POST['formid']);
                    $stringToShow = '<table id="resposesTable">';
                    $stringToShow .= '<thead>';
                    $stringToShow .= '<tr>';
                    $stringToShow .= '<th>';
                    $stringToShow .= 'ID';
                    $stringToShow .= '</th>';
                    $stringToShow .= '<th>';
                    $stringToShow .= 'User Name';
                    $stringToShow .= '</th>';
                    $stringToShow .= '<th>';
                    $stringToShow .= 'Phone';
                    $stringToShow .= '</th>';
                    $stringToShow .= '<th>';
                    $stringToShow .= 'Creation Date';
                    $stringToShow .= '</th>';
                    $stringToShow .= '</tr>';
                    $stringToShow .= '</thead>';
                    $stringToShow .= '<tbody>';
          

                    //return json_encode($resposnessArray);
                    for($i = 0 ; $i < count($resposnessArray) ; $i++){
                        $stringToShow .='<tr class="responseformRows" userid="'.$resposnessArray[$i]['id'].'" formid="'.$_POST['formid'].'">';
                        $stringToShow .='<td>'.$resposnessArray[$i]['id'].'</td><td>'.$resposnessArray[$i]['name'].'</td><td>'.$resposnessArray[$i]['phone'].'</td><td>'.$resposnessArray[$i]['date'].'</td>';
                        $stringToShow .='</tr>';
                    }
                    $stringToShow .= '</tbody>';
                    $stringToShow .= '</table>';

                    return $stringToShow ;
                }

            }
        }
        if(isset($_GET['formid'])){
         
            $formHeader = new FormsHeader;
            $formName = $formHeader->getFormHeader($_GET['formid']);
            if(count($formName)>0){
                $formName = $formName[0]['name'];
            }
            else{
                return $this->render('response');
            }
            $accounts = new Accounts;
            $variants = new Variants;
            $accounts = $accounts->getAccountsById($_GET['accountid']);
            if(count($accounts)>0){
                $accountName =  $accounts[0]['name'];
                $accountPhone =  $accounts[0]['phone'];
            }
      
  
            $string  ='<div class="container-fluid">';
            $string  .='<div class="row justify-content-center">';
            $string .='<div class="col-6" id="question_div">';
            //$string .='';
            $string .= '<div class="row">';
            $string .= '<div class="main_question_div main_question_header">';
            $string .= '<input type="text" id="question_header" placeholder="fff" disabled value="'.$formName.'" class="question_header">';
            $string .= '</div>';
            $string .= '<div class="container">';
            $string .= '<div class="main_question_div" ><table>';
            $string .= '<tr><td>Անուն Ազգանուն   </td><td><span style="font-weight:600;">'.$accountName.'</span></td></tr>';
            $string .= '<tr><td>Հեռախոսահամար   </td><td><span style="font-weight:600;">'. $accountPhone.'</span></td></tr>';
            $string .= '</table><input type="text" id="form_id" value="'.$_GET['formid'].'" style="display:none;"/>';
            $string .= '</div>';
            $string .= '</div>';
            $questions = new Questions;
            $answers = new Answers;
         







            $questionsHeader = $questions->getQuestions($_GET['formid']);
                for($i = 0 ; $i<count($questionsHeader);$i++){


                   

                    $string .= '<div class="container">';
                    $string .= '<div class="main_question_options" action="'.$questionsHeader[$i]['action'].'" question="'.$questionsHeader[$i]['id'].'" row="'.$i.'">';

                    $string .= '<input disabled type="text" row="'.$i.'" value="'.$questionsHeader[$i]['name'].'" class="option_header question_option_header option_header_'.$i.'" id="option_header_'.$i.'">';
                    
                    $answersArray = $answers->getAnswers($questionsHeader[$i]['id']);
                    for($b = 0 ; $b<count($answersArray);$b++){
                        if($questionsHeader[$i]['action']!='textarea'){
                            $answerssArray = $variants->getVariants($_GET['accountid'],$_GET['formid'],$questionsHeader[$i]['id'],$answersArray[$b]['id']);
                            if(count($answerssArray)>0){
                                $string .= '<label><input disabled checked type="'.$questionsHeader[$i]['action'].'"  value="'.$answersArray[$b]['id'].'" name="answer_'.$i.'" class="question_option '.$questionsHeader[$i]['action'].'_'.$i.'"> '.$answersArray[$b]['name'].'</label><br>';
                            }
                            else{
                                $string .= '<label><input disabled type="'.$questionsHeader[$i]['action'].'"  value="'.$answersArray[$b]['id'].'" name="answer_'.$i.'" class="question_option '.$questionsHeader[$i]['action'].'_'.$i.'"> '.$answersArray[$b]['name'].'</label><br>';
                            }
                        }
                    }
                    if($questionsHeader[$i]['action']=='textarea'){
                        $answerssArray = $variants->getVariants($_GET['accountid'],$_GET['formid'],$questionsHeader[$i]['id'],'');
                        if(count($answerssArray)>0){
                            $string .= '<textarea  disabled name="answer_'.$i.'"  id="answer_'.$i.'" cols="70" class="question_option '.$questionsHeader[$i]['action'].'_1">'.$answerssArray[0]['text'].'</textarea>';
                        }
                        else{
                            $string .= '<textarea  disabled name="answer_'.$i.'"  id="answer_'.$i.'" cols="70" class="question_option '.$questionsHeader[$i]['action'].'_1"></textarea>';

                        }
                    }
                    $string .= '</div>';
                    $string .= '</div>';
                }
         
            // $string .= '<input onclick="sendForm()" type="button" style="width:100%;margin-top:20px;" class="btn btn-info" value="Ուղարկել"/>';
            $string .= '</div>';
            $string .= '</div>';
            $string .= '</div>';
            $string .= '</div>';    
            $this->layout = 'index';
            return $this->render('response',['string'=>$string]);
        }
        else{
            $this->layout = 'index';
            return $this->render('response');
        }


    }
    public function actionSettings()
    {
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->isAjax) {
                if(isset($_POST['actionName'])){
                    return $this->redirect(['site/'.$_POST['actionName']]);
                }

            }
        }
        $this->layout = 'index';
        return $this->render('settings');

    }
    public function actionShare()
    {
        $this->layout = 'index';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->isAjax) {
                if(isset($_POST['actionName'])){
    
                }

            }
        }
        return $this->render('share');

    }
    public function actionForm()
    {
        $this->layout = 'form';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->isAjax) {
                if(isset($_POST['saveForm']) && isset($_POST['form'])){
                        $variants = new Variants;
                        $form = $_POST['form'];
                        $form =  json_decode($form);                   
                        $accounts = new Accounts;                             
                        $accounts->saveAccounts($form[0]->formUsername,$form[0]->formPhone);

                        $accountid = $accounts->getAccounts($form[0]->formUsername,$form[0]->formPhone);
                        $accountid = $accountid[0]['id'];

              

                        for($i = 0 ; $i < count($form); $i++){
                            if(isset($form[$i]->answer)){
                                $answer_id = $form[$i]->answer;
                            
                                $answcheck = 0;
                                 }
                            $total = count((array)$answer_id);                       
          
                            $arrCount = 0;
                            while ($arrCount < $total && is_array($answer_id)) {
                                $answcheck++;
                                $answer = $answer_id[$arrCount];         
                                $formid = $form[$i]->formId;
                                $question_id = $form[$i]->questionId;                     
                                $text = $form[$i]->text;
                                $user = $accountid;
                                $action = $form[$i]->action;
                                $variants->saveVariants($formid,$question_id,$answer,$text,$user,$action); 
                                $arrCount++;
                            }
                            if($answcheck==0){
                                if(isset($form[$i]->answer)){
                                    $answer = $form[$i]->answer;  
                                    $formid = $form[$i]->formId;
                                    $question_id = $form[$i]->questionId;                     
                                    $text = $form[$i]->text;
                                    $user = $accountid;
                                    $action = $form[$i]->action;
                                    $variants->saveVariants($formid,$question_id,$answer,$text,$user,$action);  
                                }
                      
                     
                            }

                            $arrCount = array();
        
                        }
                        return 'ok';
                }

            }
        }
        if(isset($_GET['id'])){

            $formHeader = new FormsHeader;
            $formsName = $formHeader->getFormHeader($_GET['id']);
            //return json_encode($formName);
            if(count($formsName)>0){
                $formName = $formsName[0]['name'];
                $formStyle = $formsName[0]['style'];
            }
            else{
                return $this->render('form');
            }
            $string  ='<div class="container-fluid">';
            $string  .='<div class="row justify-content-center">';
            $string .='<div class="col-6" id="question_div">';
            $string .= '<div class="row">';
            // $string .= '<form onsubmit="checkForm(this);">';    

            $string .= '<span style="color:red;font-size:600;">Լրացրեք դաշտերը</span>';


            $string .= '<div class="main_question_div main_question_header '.$formStyle.'">';
            $string .= '<input type="text" id="question_header" disabled value="'.$formName.'" class="question_header">';
            $string .= '</div>';

  
            $string .= '<div class="container">';
            $string .= '<div class="main_question_div '.$formStyle.'" ><input type="checkbox" onclick="hideThis(this)"/><span> Hide Element</span><table>';
            $string .= '<tr><td>Անուն Ազգանուն </td><td><input placeholder="Գրեք Ձեր անունը և ազգանունը" type="text" id="form_username"  class="question_header"></label></td></tr>';
            $string .= '<tr><td>Հեռախոսահամար </td><td><input placeholder="Գրեք Ձեր հերախոսահամարը" type="text" id="form_phone"  class="question_header"></td></tr>';
            $string .= '</table><input type="text" id="form_id" value="'.$_GET['id'].'" style="display:none;"/>';
            $string .= '</div>';
            $string .= '</div>';


            $questions = new Questions;
            $answers = new Answers;

            //getAnswers
            $questionsHeader = $questions->getQuestions($_GET['id']);
  
                for($i = 0 ; $i<count($questionsHeader);$i++){
                    $string .= '<div class="container">';
                    if($questionsHeader[$i]['require']==1){
                        $string .= '<div class="main_question_options '.$formStyle.'" action="'.$questionsHeader[$i]['action'].'" question="'.$questionsHeader[$i]['id'].'" row="'.$i.'"><label><span style="font-size:9px;color:red;">Պարտադիր դաշտ</span></label>';

                    }
                    else{
                        $string .= '<div class="main_question_options '.$formStyle.'" action="'.$questionsHeader[$i]['action'].'" question="'.$questionsHeader[$i]['id'].'" row="'.$i.'">';
                    }

                    $string .= '<input disabled type="text" row="'.$i.'" value="'.$questionsHeader[$i]['name'].'" class="option_header question_option_header option_header_'.$i.'" id="option_header_'.$i.'">';
                    
                    $answersArray = $answers->getAnswers($questionsHeader[$i]['id']);
                    for($b = 0 ; $b<count($answersArray);$b++){
                        if($questionsHeader[$i]['action']!='textarea'){
                            if($b==0 && $questionsHeader[$i]['require']==1){
                                $string .= '<label><input required type="'.$questionsHeader[$i]['action'].'"  value="'.$answersArray[$b]['id'].'" name="answer_'.$i.'" class="question_option '.$questionsHeader[$i]['action'].'_'.$i.'"> '.$answersArray[$b]['name'].'</label><br>';

                            }
                            else{
                                $string .= '<label><input type="'.$questionsHeader[$i]['action'].'"  value="'.$answersArray[$b]['id'].'" name="answer_'.$i.'" class="question_option '.$questionsHeader[$i]['action'].'_'.$i.'"> '.$answersArray[$b]['name'].'</label><br>';

                            }
                            
                        }
                    }
                    if($questionsHeader[$i]['action']=='textarea'){
                        $string .= '<textarea   name="answer_'.$i.'"  id="answer_'.$i.'" cols="70" class="question_option '.$questionsHeader[$i]['action'].'_1"></textarea>';
                    }
                    $string .= '</div>';
                    $string .= '</div>';
                }
     
            


            $string .= '<input onclick="sendForm()" type="button" style="width:100%;margin-top:20px;" class="btn btn-info" value="Ուղարկել"/>';

            $string .= '</div>';
            $string .= '</div>';
            $string .= '</div>';
            $string .= '</div>';    
            return $this->render('form',['string'=>$string]);
        }
        else{

            $string = '<span style="font-size:20px;font-weight:600;text-align:center;">Thanks for your attention<span>';
            return $this->render('form',['confirmed'=>$string]);
        }
        

    }
    public function actionQuestion()
    {
        $this->layout = 'index';
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->isAjax) {
                if(isset($_POST['actionName'])){
                    return $this->redirect(['site/'.$_POST['actionName']]);
                }
                if(isset($_POST['createForm']) && isset($_POST['form'])){               
                    $form =  json_decode($_POST['form']);
                    $creationDate =  $form->creationDate;
                    $formName =  $form->formName;
                    $tokens = md5($formName.'-'.Date('Y-m-d h:i:s'));
                    $questions =  $form->questions;
                    $formHeader = new FormsHeader;
                    $formStyle = $form->style;
                    $formHeader->saveFormHeader($formName,$creationDate,$questions,$tokens,$formStyle);
                    $formHeaderId = $formHeader->getFormHeaderId($tokens);
                    $formHeaderId = $formHeaderId[0]['id'];
                
                    $saveQuestions = new Questions;
                    for($i = 0 ; $i < count($questions); $i++){
                        $saveQuestions->saveQuestions($formHeaderId,$questions[$i]->question,$questions[$i]->action,$questions[$i]->require);
                        $saveId = $saveQuestions->getQuestionId($formHeaderId);
                        $saveId = $saveId[0]['id'];
                        $saveAnswers = new Answers;
                        $answerArray = $questions[$i]->answers;
                        if($questions[$i]->action!='textarea'){
                            for($b = 0 ; $b<count($answerArray);$b++){
                                $saveAnswers->saveAnswers($saveId,$answerArray[$b]);
                            }  
                        }                           
                    }
                    return $formHeaderId;
                }
            }
        }
        if(isset($_GET['form'])){
            return $this->render('question',['form'.$_GET['form']]);
        }     
        return $this->render('question');

    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    
}
