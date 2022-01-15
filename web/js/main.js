var allMenuTabs = document.getElementsByClassName('header_li');
for(var i = 0 ; i < allMenuTabs.length; i++){
    allMenuTabs[i].addEventListener('click',function(){
        doAction(this.getAttribute('actionName'));
    })
}
function doAction(actionName){
    return $.ajax({
        url: "",
        method:'POST',
        data:{'actionName':actionName}
    });

}
var addOptionButton = document.getElementById('add_option');
if(addOptionButton){
    addOptionButton.addEventListener('click',function(){
        if(addOptionButton.classList.contains('opened')){
            document.getElementById('add_option_div').style.display='none';
            addOptionButton.classList.remove('opened')
        }
        else{
            addOptionButton.classList.add('opened');
            document.getElementById('add_option_div').style.display='block';
        }
    })
}

var addOptionItem = document.getElementsByClassName('add_option_item');
for(var i = 0 ; i < addOptionItem.length; i++ ){
    addOptionItem[i].addEventListener('click',function(){
        addNewOption(this.getAttribute('action'));
    })
}
function addNewOption(option){
    var allItems = document.getElementsByClassName('main_question_options');
    var currentRow = allItems.length + 1;    
    document.getElementById('question_div').appendChild(window['add'+option+'Option'](currentRow));
}

function addNewOptionChild(e){

    var elementParentRow = e.parentNode.getAttribute('row');
    var elementParentAction = e.parentNode.getAttribute('action');
    var allItems = document.getElementsByClassName(elementParentAction+'_'+elementParentRow);
    var currentRow = allItems.length + 1;    
    document.getElementById(elementParentAction+'_'+elementParentRow).appendChild(window['add'+elementParentAction+'OptionChild'](currentRow,elementParentRow));
}
function addcheckboxOption(row){
    var container = document.createElement("div");
    var returnString = '';
    returnString+='<div class="main_question_options" action="checkbox" id="checkbox_'+row+'"  row="'+row+'">';
    returnString+='<button class="remove_item_addition" onclick=removeRow("option_header_'+row+'")>remove | </button>';
    returnString+='<label><input type="checkbox" checked id="option_header_require_'+row+'" /> required</label><br>';
    returnString+='<input type="text" row="'+row+'" placeholder="Գրեք հարցի անվանումը" class="option_header question_option_header option_header_'+row+'" id="option_header_'+row+'" /><br>';  

    returnString+='<input type="checkbox" /><input type="text" placeholder="Գրեք պատասխանի տարբերակը" action="checkbox" row="1" class="addedinput question_option checkbox_'+row+'" />';
    returnString+='<button class="add_item_addition" onclick="addNewOptionChild(this)">+</button>';
   

    returnString+='</div>';
    container.innerHTML = returnString;
    return container;
}
function addradiobuttonOption(row){
    var container = document.createElement("div");
    var returnString = '';
    returnString+='<div class="main_question_options" action="radio" id="radio_'+row+'"  row="'+row+'">';
    returnString+='<button class="remove_item_addition" onclick=removeRow("option_header_'+row+'")>remove | </button>';
    returnString+='<label><input type="checkbox" checked id="option_header_require_'+row+'" /> required</label><br>';
    returnString+='<input type="text" row="'+row+'" placeholder="Գրեք հարցի անվանումը" class="option_header question_option_header option_header_'+row+'" id="option_header_'+row+'"/><br>';

    returnString+='<input type="radio" /><input type="text" placeholder="Գրեք պատասխանի տարբերակը"  action="radio" row="1" class="addedinput question_option radio_'+row+'" />';
    returnString+='<button class="add_item_addition"  onclick="addNewOptionChild(this)">+</button>';
    returnString+='</div>';
    container.innerHTML = returnString;
    return container;
}
function addtextOption(row){
    var container = document.createElement("div");
    var returnString = '';
    returnString+='<div class="main_question_options" action="textarea" id="textarea_'+row+'" row="'+row+'">';
    returnString+='<button class="remove_item_addition" onclick=removeRow("option_header_'+row+'")>remove | </button><br>';
    returnString+='<input type="text" row="'+row+'" placeholder="Գրեք հարցի անվանումը" class="option_header question_option_header option_header_'+row+'" id="option_header_'+row+'"/>';
    // returnString+='<input type="checkbox" /><input type="text"  action="textarea" row="1" class="question_option text_'+row+'" />';

    returnString+='</div>';
    container.innerHTML = returnString;
    return container;
}

function addcheckboxOptionChild(row,parentRow){
    var container = document.createElement("div");
    var returnString = '';
    returnString+='<input type="checkbox" /><input type="text" placeholder="Գրեք պատասխանի տարբերակը" action="checkbox" id="checkbox_'+parentRow+'_'+row+'" row="'+row+'" class="addedinput question_option checkbox_'+parentRow+'" />';
    returnString+='<button class="remove_item_addition" onclick=removeRow("checkbox_'+parentRow+'_'+row+'")>-</button>';
    container.innerHTML = returnString;
    return container;
}
function addradioOptionChild(row,parentRow){
    var container = document.createElement("div");
    var returnString = '';
    returnString+='<input type="radio" /><input type="text" placeholder="Գրեք պատասխանի տարբերակը" action="radio" row="'+row+'" id="radio_'+parentRow+'_'+row+'" class="addedinput question_option radio_'+parentRow+'" />';
    returnString+='<button class="remove_item_addition" onclick=removeRow("radio_'+parentRow+'_'+row+'")>-</button>';
    container.innerHTML = returnString;
    return container;
}
function removeRow(element){
    document.getElementById(element).parentNode.remove();
}   

var saveButton = document.getElementById('save_option');
if(saveButton){
    saveButton.addEventListener('click',function(){
        var questionHeader = document.getElementById('question_header').value;
        var questions = document.getElementsByClassName('main_question_options');
        var chooseFormStyle = document.getElementById('styleForm').value;
        var currentDate = new Date();
        var year = currentDate.getFullYear();
        var month = (currentDate.getMonth()+1);
        if(month<10){
            month = '0'+month;
        }
        var day = currentDate.getDate();
        if(day<10){
            day = '0'+day;
        }
        var date = year+'-'+month+'-'+day;
        var answerObject = [];
        var answerArray = [];
        for(var i = 0 ; i < questions.length; i++){
            var row = questions[i].getAttribute('row');
            var action = questions[i].getAttribute('action');
            var questionHeaderChild = document.getElementById('option_header_'+row).value;
            var require = 0; 
            //alert('option_header_require_'+row);
            if(!!document.getElementById('option_header_require_'+row)){
                var requireHeader = document.getElementById('option_header_require_'+row).checked;

                if(requireHeader){
                    var require = 1;   
                }
                else{
                    var require = 0;  
                }
            }
  



            if(action=='textarea'){
                var chooseOptions = [];
            }
            else{
                var chooseOptions = document.getElementsByClassName(action+'_'+row);
            }
            for(var b = 0 ; b < chooseOptions.length; b++){
                answerArray[b] = chooseOptions[b].value;
            }
            console.log(answerArray);
            var questionObject = {
                question : questionHeaderChild,
                require: require,
                action  : action,
                answers :  answerArray
            };
            answerObject.push(questionObject);
            answerArray = [];
        }
        var sendObject = {
            formName : questionHeader,
            creationDate:date,
            questions:answerObject,
            style:chooseFormStyle
        };
        sendObject = JSON.stringify(sendObject);
        console.log(sendObject);
        $.ajax({
            url: "",
            method:'POST',
            data:{'createForm':'true','form':sendObject},
            success: function(result){
               
                location.href =  window.location.href+'?'+insertParam('form',result);
                // document.location.reload();
            
            }
        });
    
    })
}
//$("input[type='radio'][name='rate']:checked").val();



function sendForm(){
   var form_id = document.getElementById('form_id').value;
   var form_username = document.getElementById('form_username').value;
   var form_phone = document.getElementById('form_phone').value;
   var row = 0;
   var question = 0;
   var action = ' ';
   var allAnswers = document.getElementsByClassName('main_question_options');
   var sendForm = [];
   for(var i = 0 ; i < allAnswers.length; i ++){
        row = allAnswers[i].getAttribute('row');
        question = allAnswers[i].getAttribute('question');
        action = allAnswers[i].getAttribute('action');
        if(action=='checkbox'){
            var answer = [];
           

            var checkedValue = null; 
            var inputElements = document.getElementsByClassName('checkbox_'+row);
            for(var is=0; inputElements[is]; is++){
                if(inputElements[is].checked){
                    checkedValue = inputElements[is].value;
                    if(checkedValue){
                        answer.push(checkedValue);
                    }
       
                }
            }
            var text = '';

        }
        else if(action=='textarea'){
            var text = document.getElementById('answer_'+row).value
            var answer = 0;
        }
        else if(action=='radio'){
            var text = '';
            var answer = $("input[type='radio'][name='answer_"+row+"']:checked").val();
        }
        var sendObject = {
            formId:form_id,
            formUsername:form_username,
            formPhone:form_phone,
            questionId:question,
            answer:answer,
            action:action,
            text:text
        }
        sendForm.push(sendObject);
      
   }
  // console.log(sendForm);
  sendForm = JSON.stringify(sendForm);
  console.log(sendForm);

  var elem = document.getElementsByTagName('input');
  let isok = 0;
  let crow = 0;
   for(var bo = 0 ; bo < elem.length; bo++){
       if(elem[bo].classList.contains('question_option') && elem[bo].hasAttribute('required')){
        // let elementName =elem[bo].name;
        // let currentElement = document.getElementsByName(elementName);
       // alert(currentElement[0].value);
         let actionc = elem[bo].getAttribute('type');
         let rowc = elem[bo].parentNode.parentNode.getAttribute('row');
         let allRequiredRows = document.getElementsByClassName(actionc+'_'+rowc);
         for(var ch = 0 ; ch < allRequiredRows.length; ch++){
            if(allRequiredRows[ch].checked){
                crow++;
            }
         }
         if(crow<1){
            isok=1; 
         }
       }

   }
// if(elem.hasAttribute('required')){
// alert(elem.value);
// }
if(isok==1){
                   Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Լրացրեք պարտադիր դաշտերը',
                    showConfirmButton: false,
                    timer: 1500
              })  
}
else{
    $.ajax({
        url: "",
        method:'POST',
        data:{'saveForm':'true','form':sendForm},
        success: function(result){
            console.log(result);
            if(result=='ok'){

                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Ուղարկված է',
                    showConfirmButton: false,
                    timer: 1500
              }) 
                // document.location.reload();
            }
        }
    });
}

}


function checkForm(form)
{
  if(!form.terms.checked) {
    alert("Please indicate that you accept the Terms and Conditions");
    form.terms.focus();
    alert(false);
  }
  alert(true);
}

function findResponses(){
    var dateFrom = document.getElementById('findDateFrom').value;
    var dateTo = document.getElementById('findDateTo').value;

    $.ajax({
        url: "",
        method:'POST',
        data:{'findResponses':'true','dateFrom':dateFrom,'dateTo':dateTo},
        success: function(result){
            document.getElementById('responseContent').innerHTML = result;

            var allResponseRows = document.getElementsByClassName('responseformRows');
            for(var i = 0 ; i < allResponseRows.length; i++){
                allResponseRows[i].addEventListener('click',function(){
                    openResponseForm(this.getAttribute('formid'))
                })
            }
        }
    });
}


function openResponseForm(formid){
    $.ajax({
        url: "",
        method:'POST',
        data:{'findResponsesByForm':'true','formid':formid},
        success: function(result){
            document.getElementById('responseContent').innerHTML = result;

            $.ajax({
                url: "",
                method:'POST',
                data:{'findFormDetails':'true','formid':formid},
                success: function(chart){
                    chart = JSON.parse(chart);
                    console.log(chart);
                    for(var i = 0 ; i < chart.length; i++){
                        document.getElementById('chart_div').innerHTML += '<canvas id="myChart_'+i+'" style="width:100%;max-width:600px"></canvas>';
                        console.log(chart[i]['answers']);
                    }

                    for(var i = 0 ; i < chart.length; i++){
                        var answers = chart[i]['answers'];
                        var userscount = chart[i]['users'];
                        var xValues = [];
                        var yValues = [];
                        xValues.push('');
                        yValues.push(0);
                        for(var b = 0 ; b < answers.length; b++){
                           // console.log(answers['name']);
                            xValues.push(answers[b]['name']);
                            yValues.push(((answers[b]['count']/userscount)*100));
                        }
                        // xValues.push('');
                        yValues.push(100);
                       // var xValues = ["Italy", "France", "Spain", "USA", "Argentina"];
                        //var yValues = [55, 49, 44, 24, 15];
                        var barColors = ["red", "green","blue","orange","brown"];
                        
                        new Chart("myChart_"+i, {
                          type: "bar",
                          data: {
                            labels: xValues,
                            datasets: [{
                              backgroundColor: barColors,
                              data: yValues
                            }]
                          },
                          options: {
                            legend: {display: false},
                            title: {
                              display: true,
                              text:chart[i]['name']
                            }
                          }
                        });
                    }
      
         

              

        
        
                    var allResponseRows = document.getElementsByClassName('responseformRows');
                    for(var i = 0 ; i < allResponseRows.length; i++){
                        allResponseRows[i].addEventListener('click',function(){
        
                           insertParamForResponse(this.getAttribute('formid'),this.getAttribute('userid'));
                          
                        })
                    }

                }
            })
        }
    });
}


function insertParamForResponse(formid,userid){
 
        insertParam('formid',formid);
        insertParam('accountid',userid);


       // insertParam('form',result);
       location.href =  window.location.href+'?'+insertParam('formid',formid)+''+insertParam('accountid',userid);

       // insertParam('accountid='+userid+'&formid='+formid,'');
        // document.location.reload();
}



function insertParam(key, value) {
    key = encodeURIComponent(key);
    value = encodeURIComponent(value);

    // kvp looks like ['key1=value1', 'key2=value2', ...]
    var kvp = document.location.search.substr(1).split('&');
    let i=0;

    for(; i<kvp.length; i++){
        if (kvp[i].startsWith(key + '=')) {
            let pair = kvp[i].split('=');
            pair[1] = value;
            kvp[i] = pair.join('=');
            break;
        }
    }

    if(i >= kvp.length){
        kvp[kvp.length] = [key,value].join('=');
    }

    // can return this or...
    let params = kvp.join('&');

    // reload page with new params
   // document.location.search = params
   return params;
}


var changeStyleBtn = document.getElementById('change_style');
if(changeStyleBtn){
changeStyleBtn.addEventListener('click',function(){
    var select = '<h4>Choose Style</h4><label class="text-left w-100 " style="font-size:18px ;">Type</label><select id="chooseFormStyle" style="font-size:14px!important;" class="form-control">';
   // for(var i = 0 ; i < departments.length;i++ ){
            select += "<option  value='classic'>Classic</option>";
            select += "<option  value='soft'>Soft</option>";
            select += "<option  value='soft'>Modern</option>";
    //}
   
    select += '</select>';
    Swal.fire({
            title: select,
            showCancelButton: true,
            confirmButtonText: 'Պահպանել',
            cancelButtonText: 'Չեղարկել',
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {         
               document.getElementById("styleForm").value = document.getElementById('chooseFormStyle').value;

            Swal.fire('Saved!', '', 'success');
        
            } else if (result.isDenied) {
              Swal.fire('Changes are not saved', '', 'info')
            }
          })
})
}
function hideThis(e){
    e.parentNode.style.display="none";
}
