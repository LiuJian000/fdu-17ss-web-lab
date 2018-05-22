let button = document.getElementsByTagName("button")[0];

let colNumber = document.getElementById("colNumber");

let aboutColumn = document.querySelector(".aboutColumn");

let tbCreate = document.querySelector(".tbCreate");

let layTable = document.querySelector(".lay-table");

let infor = document.querySelector(".infor");

let select2 = document.getElementById("select2");

let select1 = document.getElementById("select1");

let aboutTb = document.querySelector(".aboutTb");

let columnsInput = [];//储存输入框信息

let tables = [];//储存表格

let classes = ["", "grey"];

//列数改变，输入框个数改变
colNumber.onchange = function(){
    let number = parseInt(colNumber.value);
    if(number <= 0){
        return false;
    }else {
        button.style.display = "inline";

        aboutColumn.style.display = "block";
        showInput(number);
    }
};

//显示一定数量的输入框
function showInput(number, ths = []){
    while(aboutColumn.hasChildNodes()) //当aboutColumn下还存在子节点时 循环继续
    {
        aboutColumn.removeChild(aboutColumn.firstChild);
    }

    for(let i = 0; i < number; i++){
        columnsInput[i] = document.createElement("input");
        columnsInput[i].type = "text";
        columnsInput[i].placeholder = ths[i]? ths[i].innerHTML : "Attribute";
        columnsInput[i].style.width = 140 + "px";
        columnsInput[i].style.height = "30px";
        aboutColumn.appendChild(columnsInput[i]);
    }
}

//第一个下拉框事件
select1.onchange = function(){
    switch (this.value) {
        case "selectOne":
            button.style.display = "none";
            tbCreate.style.display = "none";
            infor.innerHTML = "";
            break;
        case "createTable":
            aboutTb.getElementsByTagName("input")[0].value = "";
            aboutTb.getElementsByTagName("input")[1].value = "";
            tbCreate.style.display = "block";
            aboutTb.style.display = "block";
            aboutColumn.style.display = "none";
            infor.innerHTML = "";
            break;
        case "addRow":
            aboutTb.style.display = "none";
            aboutColumn.style.display = "block";
            addRow();
            infor.innerHTML = "";
            break;
        case "delRow":
            aboutTb.style.display = "none";
            aboutColumn.style.display = "block";
            if(select2.value !== "select")
                showInput(tables[select2.value].getElementsByTagName("th").length,tables[select2.value].getElementsByTagName("th"));
            infor.innerHTML = "";
            break;
        case "delTable":
            tbCreate.style.display = "none";
            infor.innerHTML = "WARNING: You cannot undo this action!";
            if(select2.value === "select"){
                infor.innerHTML = '';
            }
            break;
    }
};

//第二个下拉框事件
select2.onchange = function(){
    showTable(tables[select2.value]);
    if(select2.value === "select")
        return;
    if(select1.value === "addRow" || select1.value === "delRow"){
        showInput(tables[select2.value].getElementsByTagName("th").length, numbers = tables[select2.value].getElementsByTagName("th"));
    }
};

//添加一行所显示的input
function addRow(){
    let numbers = 0;
    if(select2.value !== "select")
        numbers = tables[select2.value].getElementsByTagName("th").length;
    else {
        return;
    }
    showInput(numbers,numbers = tables[select2.value].getElementsByTagName("th"));
}

//按钮提交事件
button.onclick = function(){
    if(select1.value === "createTable"){
        let tbName = document.getElementById("tbName").value || "tableName";
        addOption(tbName);
        tbName = handleTaName(tbName);
        tables[tbName] = document.createElement("table");
        let thead = document.createElement("thead");
        for(let i = 0; i < parseInt(colNumber.value); i++){
            let th = document.createElement("th");
            th.innerHTML = columnsInput[i].value || "Attribute";
            thead.appendChild(th);
        }
        tables[tbName].appendChild(thead);
        showTable(tables[tbName]);
        //清空表格创建信息
        aboutTb.getElementsByTagName("input")[0].value = "";
        aboutTb.getElementsByTagName("input")[1].value = "";
        showInput(0);

    }else if(select1.value === "addRow"){
        let ok = false;
        let tr = document.createElement("tr");
        for(let i = 0; i < tables[select2.value].getElementsByTagName("th").length; i++){
            let td = document.createElement("td");
            td.innerHTML = columnsInput[i].value;
            td.className = classes[tables[select2.value].getElementsByTagName("tr").length % 2];
            tr.appendChild(td);
            if(td.innerHTML !== "")
                ok = true;
        }
        if(!ok){
            return false;
        }
        tables[select2.value].appendChild(tr);
        showInput(tables[select2.value].getElementsByTagName("th").length,tables[select2.value].getElementsByTagName("th"));
    }else if(select1.value === "delTable"){
        delOption();
        if(select2.value === "select"){
            infor.innerHTML = '';
        }
    }else if(select1.value === "delRow"){
        if(select2.value === "select")
            return false;

        let trs = tables[select2.value].getElementsByTagName("tr");

        let ok2 = true;
        for(let i = 0; i < trs.length; i++){
            let ok = true;//匹配判断常量
            let tds = trs[i].getElementsByTagName("td");

            for(let k = 0; k < tds.length; k++){

                //改变后续行的css
                if(!ok2){
                    tds[k].className = classes[1 - classes.indexOf(tds[k].className)]
                }

                if(tds[k].innerHTML !== aboutColumn.getElementsByTagName("input")[k].value
                    && aboutColumn.getElementsByTagName("input")[k].value && ok2){
                    ok = false;
                    break;
                }

            }
            //是否删除当前行
            if(ok && ok2){
                tables[select2.value].removeChild(trs[i]);
                showInput(tables[select2.value].getElementsByTagName("th").length,tables[select2.value].getElementsByTagName("th"));
                ok2 = false;
                i--;//回退一行，css设置空隙
            }
        }
        showInput(tables[select2.value].getElementsByTagName("th").length,tables[select2.value].getElementsByTagName("th"));
    }
    return false;
};

//为第二个下拉框添加选项并默认选中
function addOption(optionValue){

    let option = document.createElement("option");
    select2.appendChild(option);
    option.innerHTML = optionValue;
    option.value = handleTaName(optionValue);
    option.selected = true;
}

//删除选项
function delOption(){
    let options = select2.getElementsByTagName("option");

    //默认选项忽略
    if(select2.value === "select")
        return;

    for(let option of options){
        if(option.selected){
            select2.removeChild(option);
            tables[option.value] = "";
            if(layTable.firstChild)
                layTable.removeChild(layTable.firstChild);
            if(select2.getElementsByTagName("option")[1]){
                select2.getElementsByTagName("option")[1].selected = true;
                showTable(tables[select2.getElementsByTagName("option")[1].value]);
            }
            return;
        }
    }
}

//处理表格重名问题，因为采用的是数组储存表格
function handleTaName(tableName){
    let count = 0;//计数器
    for(let i in tables){
        if(i.replace( /\d+/,'') === tableName.replace( /\d+/,''))
            count++;
    }
    return tableName + count;
}

//展现表格
function showTable(table){
    if(layTable.firstChild)
        layTable.removeChild(layTable.firstChild);
    if(select2.value === "select")
        return;//传入表格对象无效
    layTable.appendChild(table);
}