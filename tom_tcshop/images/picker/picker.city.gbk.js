var name_City = document.getElementById('choseCity');

var first_City = []; /* 直辖市 */
var second_City = []; /* 市 */

//var selectedIndex_City = [0, 0]; /* 默认选中的地区 */

var checked_City = [0, 0]; /* 已选选项 */

function creatList_City(obj, list){
  obj.forEach(function(item, index, arr){
	var temp = new Object();
    temp.id = item.id;
	temp.text = item.name;
	temp.value = index;
	list.push(temp);
  })
}

creatList_City(city, first_City);

if (city[selectedIndex_City[0]].hasOwnProperty('sub')) {
  creatList_City(city[selectedIndex_City[0]].sub, second_City);
} else {
  second_City = [{text: '', value: 0}];
}

var picker_City = new Picker({
	data: [first_City, second_City],
	selectedIndex: selectedIndex_City,
	title: '区域选择'
});

picker_City.on('picker.select', function (selectedVal_City, selectedIndex_City) {
	var id1 = first_City[selectedIndex_City[0]].id;
    var text1 = first_City[selectedIndex_City[0]].text;
	var id2 = second_City[selectedIndex_City[1]].id;
    var text2 = second_City[selectedIndex_City[1]].text;

    $("#area_id").val(id1);
    $("#street_id").val(id2);
	name_City.innerText = text1 + ' ' + text2 ;
    
});

picker_City.on('picker.change', function (index, selectedIndex_City) {
  if (index === 0){
    firstChange_City();
  }

  function firstChange_City() {
    second_City = [];
    checked_City[0] = selectedIndex_City;
    var first_City = city[selectedIndex_City];
    if (first_City.hasOwnProperty('sub')) {
      creatList_City(first_City.sub, second_City);
    } else {
      second_City = [{text: '', value: 0}];
      checked_City[1] = 0;
    }

    picker_City.refillColumn(1, second_City);
    picker_City.scrollColumn(1, 0)
  }

});

picker_City.on('picker.valuechange', function (selectedVal_City, selectedIndex_City) {
  console.log(selectedVal_City);
  console.log(selectedIndex_City);
});

name_City.addEventListener('click', function () {
	picker_City.show();
});



