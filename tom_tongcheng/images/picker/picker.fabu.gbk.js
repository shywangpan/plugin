var nameEl = document.getElementById('choseCity');

var first = []; /* 省，直辖市 */
var second = []; /* 市 */

//var selectedIndex = [0, 0]; /* 默认选中的地区 */

var checked = [0, 0]; /* 已选选项 */

function creatList(obj, list){
  obj.forEach(function(item, index, arr){
	var temp = new Object();
    temp.id = item.id;
	temp.text = item.name;
	temp.value = index;
	list.push(temp);
  })
}

creatList(city, first);

if (city[selectedIndex[0]].hasOwnProperty('sub')) {
  creatList(city[selectedIndex[0]].sub, second);
} else {
  second = [{text: '', value: 0}];
}

var picker = new Picker({
	data: [first, second],
	selectedIndex: selectedIndex,
	title: '区域选择'
});

picker.on('picker.select', function (selectedVal, selectedIndex) {
	var id1 = first[selectedIndex[0]].id;
    var text1 = first[selectedIndex[0]].text;
	var id2 = second[selectedIndex[1]].id;
    var text2 = second[selectedIndex[1]].text;

    $("#area_id").val(id1);
    $("#street_id").val(id2);
	nameEl.innerText = text1 + ' ' + text2 ;
    
});

picker.on('picker.change', function (index, selectedIndex) {
  if (index === 0){
    firstChange();
  }

  function firstChange() {
    second = [];
    checked[0] = selectedIndex;
    var firstCity = city[selectedIndex];
    if (firstCity.hasOwnProperty('sub')) {
      creatList(firstCity.sub, second);
    } else {
      second = [{text: '', value: 0}];
      checked[1] = 0;
    }

    picker.refillColumn(1, second);
    picker.scrollColumn(1, 0)
  }

});

picker.on('picker.valuechange', function (selectedVal, selectedIndex) {
  console.log(selectedVal);
  console.log(selectedIndex);
});

nameEl.addEventListener('click', function () {
	picker.show();
});



