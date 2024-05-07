///////////////////////////////////////////////////////////
// Make mobile navigation work
const btnNavEl = document.querySelector(".btn-mobile-nav");
const headerEl = document.querySelector(".header");

btnNavEl.addEventListener("click", function () {
  headerEl.classList.toggle("nav-open");
});

headerEl.classList.toggle("nav-open", false);

// select
function generateComponents() {
  // 获取选择器的值
  var count = document.getElementById("answerCount").value;

  // 清空output区域
  document.getElementById("output").innerHTML = "";

  // 生成指定数量的组件
  for (var i = 0; i < count; i++) {
    // 创建<div>元素
    var div = document.createElement("div");
    div.className = "selectionBox";

    // 创建<span>元素
    var span = document.createElement("span");
    span.className = "number";
    span.textContent = (i + 1) + ".";

    // 创建<select>元素
    var select = document.createElement("select");
    select.className = "selection";

    // 添加选项到<select>元素
    var options = ["下拉式選單", "選擇題（單選）", "選取方塊（多選）"];
    var options_type = ["select","radio","checkbox"];
    for (var j = 0; j < options.length; j++) {
      var option = document.createElement("option");
      option.text = options[j];
      option.value = options_type[j];
      select.add(option);
    }

    // 将<span>元素和<select>元素添加到<div>元素
    div.appendChild(span);
    div.appendChild(select);

    // 将<div>元素添加到output区域
    document.getElementById("output").appendChild(div);
  }
}
//ajax取回php值進行轉碼
function htmldecode(text) {
  var map = {
    '&amp;': '&',
    '&#038;': "&",
    '&lt;': '<',
    '&gt;': '>',
    '&quot;': '"',
    '&#039;': "'",
    '&#8217;': "’",
    '&#8216;': "‘",
    '&#8211;': "–",
    '&#8212;': "—",
    '&#8230;': "…",
    '&#8221;': '”'
  };

  return text.replace(/\&[\w\d\#]{2,5}\;/g, function(m) { return map[m]; });
};
//圖片上傳(含圖片預覽)
function loadFile() {
  var output = document.getElementById('show_logo');
  var file_err_msg = '';
  let ext_err = 1;
  let err = false;
  for(let i=0;i<upload_files_ext_array.length;i++){
    if(event.target.files[0]['type'].indexOf(upload_files_ext_array[i])!='-1'){
      ext_err = 0;
      break;
    }
  }
  if(ext_err==1){
    err = true;
    Swal.fire({
      title: "格式錯誤",
      html: '請上傳正確格式，格式如：'+upload_files_ext_array,
      icon: "error"
    });
  }
  if (parseInt(event.target.files[0]['size']) / 1024 > upload_files_max_size){
    err = true;
    Swal.fire({
      title: "容量錯誤",
      html: '圖片容量過大，請重新上傳。檔案不可超過：' + upload_files_max_size + 'KB',
      icon: "error"
    });
  }
  if(err==true){
    document.getElementById('logo').value = "";
  }else{
    output.src = URL.createObjectURL(event.target.files[0]);//當圖片上傳後，檔案暫存在event.target.files[0](只有一個檔案的話)，並createObjectURL為指定檔案配置一個記憶體空間
  }
};