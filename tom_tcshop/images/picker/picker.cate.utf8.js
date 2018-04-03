var name_Cate = document.getElementById('choseCate');

var first_Cate = []; /* 一级分类 */
var second_Cate = []; /* 二级分类 */

//var selectedIndex_Cate = [0, 0]; /* 默认选中 */

var checked_Cate = [0, 0]; /* 已选选项 */

function creatList_Cate(obj, list){
  obj.forEach(function(item, index, arr){
	var temp = new Object();
    temp.id = item.id;
	temp.text = item.name;
	temp.value = index;
	list.push(temp);
  })
}

creatList_Cate(cate, first_Cate);

if (cate[selectedIndex_Cate[0]].hasOwnProperty('sub')) {
  creatList_Cate(cate[selectedIndex_Cate[0]].sub, second_Cate);
} else {
  second_Cate = [{text: '', value: 0}];
}

var picker_Cate = new Picker({
	data: [first_Cate, second_Cate],
	selectedIndex: selectedIndex_Cate,
	title: '分类选择'
});

picker_Cate.on('picker.select', function (selectedVal_Cate, selectedIndex_Cate) {
	var id1 = first_Cate[selectedIndex_Cate[0]].id;
    var text1 = first_Cate[selectedIndex_Cate[0]].text;
	var id2 = second_Cate[selectedIndex_Cate[1]].id;
    var text2 = second_Cate[selectedIndex_Cate[1]].text;

    $("#cate_id").val(id1);
    $("#cate_child_id").val(id2);
	name_Cate.innerText = text1 + ' ' + text2 ;
    
});

picker_Cate.on('picker.change', function (index, selectedIndex_Cate) {
  if (index === 0){
    firstChange_Cate();
  }

  function firstChange_Cate() {
    second_Cate = [];
    checked_Cate[0] = selectedIndex_Cate;
    var first_Cate = cate[selectedIndex_Cate];
    if (first_Cate.hasOwnProperty('sub')) {
      creatList_Cate(first_Cate.sub, second_Cate);
    } else {
      second_Cate = [{text: '', value: 0}];
      checked_Cate[1] = 0;
    }

    picker_Cate.refillColumn(1, second_Cate);
    picker_Cate.scrollColumn(1, 0)
  }

});

picker_Cate.on('picker.valuechange', function (selectedVal_Cate, selectedIndex_Cate) {
  console.log(selectedVal_Cate);
  console.log(selectedIndex_Cate);
});

name_Cate.addEventListener('click', function () {
	picker_Cate.show();
});



