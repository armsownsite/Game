<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\ArrayHelper;
use yii\db\Query;
use app\models\WorkerAccess;
use app\models\ActionName;
use app\models\WorkerAction;
use app\models\LogHeaderCreate;
use app\models\LogHeader;
use app\models\LogCostCreate;
use app\models\MaterialType;
use app\models\WarehouseStatus;
use app\models\WarehouseStatusCreate;

function getWorkerDetailArray($id){
    $query = new Query;
    $query->select('user_name,user_surname')
      ->from('tbl_user_details')
      ->where(['id'=>$id]);
    $rows = $query->all();
    $command = $query->createCommand();
    $rows = $command->queryAll();
    $object = (object)$rows;
    return $object;
}

function getAllWorkerDetailArray(){
      $query = new Query;
      $query->select('user_id,user_name,user_surname')
        ->from('tbl_user_details');
      $rows = $query->all();
      $command = $query->createCommand();
      $rows = $command->queryAll();
      $object = (object)$rows;
      return $object;
  }
//   function getAllDepartmentArray($id){
//       $query = new Query;
//       $query->select('t1.name as name ,t2.name as parent')
//         ->from('directories_categories t1');
//       $query->leftJoin('directories_categories t2', 't1.parent_id = t2.id');
//       for($i=0 ;$i<count($id);$i++){
//           $query->orWhere(['=','t1.id', $id[$i]]);
//       }
//       $rows = $query->all();
//       $command = $query->createCommand();
//       $rows = $command->queryAll();
//       $object = (object)$rows;
//       return $object;
//   }

  function getAllDepartmentArray(){
      $query = new Query;
      $query->select('id,department_name')
        ->from('tbl_departments');
      $rows = $query->all();
      $command = $query->createCommand();
      $rows = $command->queryAll();
      $object = (object)$rows;
      return $object;
  }

function getDepartmentArray($id){
    $query = new Query;
    $query->select('department_name')
      ->from('tbl_departments')
      ->where(['id'=>$id]);
    $rows = $query->all();
    $command = $query->createCommand();
    $rows = $command->queryAll();
    $object = (object)$rows;
    return $object;
}
function getClinicArray($id){
    $query = new Query;
    $query->select('clinic_name')
      ->from('tbl_clinics')
      ->where(['id'=>$id]);
    $rows = $query->all();
    $command = $query->createCommand();
    $rows = $command->queryAll();
    $object = (object)$rows;
    return $object;
}
function getDoctorArray($id){
    $query = new Query;
    $query->select('name')
      ->from('doctor')
      ->where(['id'=>$id]);
    $rows = $query->all();
    $command = $query->createCommand();
    $rows = $command->queryAll();
    $object = (object)$rows;
    return $object;
}
function getPatientArray($id){
    $query = new Query;
    $query->select('*')
      ->from('n_patient_cart')
      ->where(['id'=>$id]);

    $rows = $query->all();
    $command = $query->createCommand();
    $rows = $command->queryAll();
    $object = (object)$rows;
    return $object;
}

function getCountryArray($id){
    $query = new Query;
    $query->select('name')
      ->from('countries')
      ->where(['id'=>$id]);
    $rows = $query->all();
    $command = $query->createCommand();
    $rows = $command->queryAll();
    $object = (object)$rows;
    return $object;
}
function getRegionArray($id){
    $query = new Query;
    $query->select('name')
      ->from('region')
      ->where(['id'=>$id]);
    $rows = $query->all();
    $command = $query->createCommand();
    $rows = $command->queryAll();
    $object = (object)$rows;
    return $object;
}
function getCityArray($id){
    $query = new Query;
    $query->select('name')
      ->from('city')
      ->where(['id'=>$id]);
    $rows = $query->all();
    $command = $query->createCommand();
    $rows = $command->queryAll();
    $object = (object)$rows;
    return $object;
}
function getDiagnosArray($id){
    $query = new Query;
    $query->select('t1.name as name ,t2.name as parent')
      ->from('directories_categories t1');
    $query->leftJoin('directories_categories t2', 't1.parent_id = t2.id');
    for($i=0 ;$i<count($id);$i++){
        $query->orWhere(['=','t1.id', $id[$i]]);
    }
    $rows = $query->all();
    $command = $query->createCommand();
    $rows = $command->queryAll();
    $object = (object)$rows;
    return $object;
}
function getDiagnosCategoryArray($id){
    $query = new Query;
    $query->select('*')
      ->from('tbl_diagnose_category')
      ->where(['id'=>$id]);
    $rows = $query->all();
    $command = $query->createCommand();
    $rows = $command->queryAll();
    $object = (object)$rows;
    return $object;
}
function getPhoneArray($id){
    $query = new Query;
    $query->select('phone')
      ->from('n_patient_phones')
      ->where(['id'=>$id]);
    $rows = $query->all();
    $command = $query->createCommand();
    $rows = $command->queryAll();
    $object = (object)$rows;
    return $object;
}
function getDiagnosByCategoryArray($id,$patient_id){
  $tableName='';
  if($id==1){
        $tableName='diagnoses_concomitant_diseases';
        return getDiagnosesConcomitantDiseasesArray($patient_id,$tableName);
  }
  elseif($id==2){
        $tableName='n_patient_cart_diagnose_nyha';
        return getDiagnosenyhaArray($patient_id,$tableName);
  }
  elseif($id==3){
        $tableName='n_patient_cart_diagnose';
        return getDiagnosesForChildrenArray($patient_id,$tableName);
  }
}
function getDiagnosesConcomitantDiseasesArray($id){
    $query = new Query;
    $query->select('*')
      ->from('diagnoses_concomitant_diseases')
      ->where(['patient_id'=>$id]);
    $rows = $query->all();
    $command = $query->createCommand();
    $rows = $command->queryAll();
    $object = (object)$rows;
    return $object;
}
function getPatientHp($id){
      $query = new Query;
      $query->select('*')
        ->from('n_patient_disease_history')
        ->distinct()
        ->where(['patient_id'=>$id])
        ->orderBy(['id' => SORT_DESC]);
      $rows = $query->all();
      $command = $query->createCommand();
      $rows = $command->queryAll();
      $object = (object)$rows;
      return $object;
  }
  function getPatientHpRows($id){
      $query = new Query;
      $query->select('*')
        ->from('n_patient_disease_history')
        ->distinct()
        ->where(['patient_id'=>$id])
        ->orderBy(['id' => SORT_DESC]);
      $rows = $query->all();
      $command = $query->createCommand();
      $rows = $command->queryAll();
      $object = (object)$rows;
      return $rows;
  }
function getDiagnosenyhaArray($id){
    $query = new Query;
    $query->select('*')
      ->from('n_patient_cart_diagnose_nyha')
      ->where(['diagnose_id'=>$id]);
    $rows = $query->all();
    $command = $query->createCommand();
    $rows = $command->queryAll();
    $object = (object)$rows;
    return $object;
}
function getDiagnosesForChildrenArray($id){
    $query = new Query;
    $query->select('*')
      ->from('n_patient_cart_diagnose')
      ->where(['patient_id'=>$id]);
    $rows = $query->all();
    $command = $query->createCommand();
    $rows = $command->queryAll();
    $object = (object)$rows;
    return $object;
}
function getAppointedDrugs($id){
    $query = new Query;
    $query->select('*')
      ->from('n_patient_visit_drug')
      ->where(['patient_id'=>$id]);
    $rows = $query->all();
    $command = $query->createCommand();
    $rows = $command->queryAll();
    $object = (object)$rows;
    return $object;
}
function getDrugsName($id){
    $query = new Query;
    $query->select('*')
      ->from('n_drugs')
      ->where(['id'=>$id]);
    $rows = $query->all();
    $command = $query->createCommand();
    $rows = $command->queryAll();
    $object = (object)$rows;
    return $object;
}
function allowCreateAccess($graphdate,$departmentId){
      $allowCreateAccessDetails = new WarehouseStatus(); 
      $currentallowCreateAccessDetails = $allowCreateAccessDetails->getWarehouseStatus($departmentId,$graphdate);
      if(count($currentallowCreateAccessDetails)>0){
            return false;
      }
      else{
            return true;
      }
}
function createAccessUnLock($graphdate,$departmentId){
      $removeCreateAccessDetails = new WarehouseStatus(); 
      $currentRemoveCreateAccessDetails = $removeCreateAccessDetails->removeWarehouseStatus($departmentId,$graphdate);
      return true;
}
function allowAccess($actionName,$workerId){
      $actionNameDetails = new ActionName(); 
      $currentActionNameDetails = $actionNameDetails->getActionName($actionName);
      $actionId = 0;
      if(count($currentActionNameDetails)>0){
            $actionId = $currentActionNameDetails[0]['id'];
      }
      else{
            return false;
      }
      $workerAction = new WorkerAction(); 
      $workerActionDetails = $workerAction->getWorkerAction($actionId);
      $accessAllowGroupId = array();
      if(count($workerActionDetails)>0){
            for($algi = 0 ; $algi<count($workerActionDetails);$algi++){
                  $accessAllowGroupId[$algi]=$workerActionDetails[$algi]['access_group_id'];
            }
      }
      if(count($accessAllowGroupId)>0){      
            $workerDetails = new WorkerAccess(); 
            $currentWorkerDetails = $workerDetails->getWorkerAccess($workerId);
            if(count($currentWorkerDetails)>0){
                for($cwa = 0 ;$cwa < count($currentWorkerDetails);$cwa++ ){                     
                      $allowAccess = in_array($currentWorkerDetails[$cwa]['group_id'],$accessAllowGroupId);                  
                      if($allowAccess){                      
                            return true;
                      }
                      else{
                            $allowAccess = false;
                      }
                }
            }
      }
      return $allowAccess;     
}
function actionHistoryCreate($actionName,$workerId){
    $actionNameDetails = new ActionName(); 
    $currentActionNameDetails = $actionNameDetails->getActionName($actionName);
    $actionId = 0;
    if(count($currentActionNameDetails)>0){
          $actionId = $currentActionNameDetails[0]['id'];
    }
    else{
          return false;
    }    
    $thisDate = date('Y-m-d');
    $thisTime = date('H:i:s');
    $getLogHeaderDetails =new LogHeader();
    $logHeaderDetailsArray = $getLogHeaderDetails->getLogHeaderDetails($actionId,$workerId,$thisDate,$thisTime);
    if(count($logHeaderDetailsArray)<1){
        $actionHistoryCreateDetails = new LogHeaderCreate();
        $actionHistoryCreateDetails->scenario = LogHeaderCreate:: SCENARIO_CREATE;
        $actionHistoryCreateDetailsArray =array("worker_id"=>$workerId,"action_id"=>$actionId,"creation_date"=>$thisDate,'creation_time'=>$thisTime);  
        $actionHistoryCreateDetails->attributes = $actionHistoryCreateDetailsArray;
        if($actionHistoryCreateDetails->validate()){
              $actionHistoryCreateDetails->save();
              return true; 
        }
        else{
              return false;
        }
    }
    else{
          return true;
    }
}
function logCreate($materialType,$actionName,$workerId,$patientId,$pid,$pquantity,$pdate,$ptime,$departmentId,$clinicId,$drugDateId){
      $actionNameDetails = new ActionName(); 
      $currentActionNameDetails = $actionNameDetails->getActionName($actionName);
      $actionId = 0;
      if(count($currentActionNameDetails)>0){
            $actionId = $currentActionNameDetails[0]['id'];
      }
      else{
            return false;
      }
      $workerActionDetails = new WorkerAction(); 
      $currentWorkerActionDetails = $workerActionDetails->getWorkerAction($actionId);
      $actionTable = '';
      if(count($currentWorkerActionDetails)>0){
            $actionTable = $currentWorkerActionDetails[0]['action_table_name'];
      }
      else{
            return false;
      }      
      $thisDate = date('Y-m-d');
      $thisTime = date('H:i:s');
      if($actionTable!='tbl_department_move'){
            return createDetailLog($materialType,$actionId,$workerId,$patientId,$pid,$pquantity,$thisDate,$thisTime,$departmentId,$clinicId,$pdate,$ptime,$drugDateId);
      }
}

function createDetailLog($materialType,$actionId,$workerId,$patientId,$pid,$pquantity,$thisDate,$thisTime,$departmentId,$clinicId,$pdate,$ptime,$drugDateId){
      $getMaterialTypeDetails =new MaterialType();
      $getMaterialTypeDetailsArray = $getMaterialTypeDetails->getTypeIdByName($materialType);
      if(count($getMaterialTypeDetailsArray)>0){
            $materialType = $getMaterialTypeDetailsArray[0]['id'];
      }
      else{
            $materialType = 1;
      }
      $getLogHeaderDetails =new LogHeader();
      $logHeaderDetailsArray = $getLogHeaderDetails->getLogHeaderDetails($actionId,$workerId,$thisDate,$thisTime);
      if(count($logHeaderDetailsArray)>0){
            $historyId = $logHeaderDetailsArray[0]['id'];
            $logCreateDetails = new LogCostCreate();
            $logCreateDetails->scenario = LogCostCreate:: SCENARIO_CREATE;
            $logCreateDetailsArray =array("history_id"=>$historyId,"worker_id"=>$workerId,"patient_id"=>$patientId,"product_type_id"=>intval($materialType),"product_id"=>$pid,"product_quantity"=>$pquantity,"date"=>$pdate,"time"=>$ptime,"department_id"=>$departmentId,"clinic_id"=>$clinicId,"graph_id"=>$drugDateId);  
            $logCreateDetails->attributes = $logCreateDetailsArray;            
            if($logCreateDetails->validate()){
                $logCreateDetails->save();
                return true;
            }
            else{
                  return false;
            }
      }
      else{
           return false;
      }
}

function sendGmail($text){
      Yii::$app->mailer->compose()
      ->setFrom('armsownsite@gmail.com')
      ->setTo('armsownsite@gmail.com')
      ->setSubject('Medical Center Error')
      ->setTextBody('Plain text content')
      ->setHtmlBody($text)
      ->send();
}
 ?>
