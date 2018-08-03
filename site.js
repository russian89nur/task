//Пагинация
function pagination(){
	var pagination__input = document.getElementsByClassName('pagination__input');
	for (var i = 0; i < pagination__input.length; i++) {
		pagination__input[i].onclick = function () {
			var pagination__tr = document.getElementsByTagName('tr'),
				pagination_true = document.getElementsByClassName('pagination_true'),
				pagin_length = pagination_true.length;
			for (var num_pag = 0; num_pag < pagin_length; num_pag++){
				pagination_true[0].className = 'pagination';
			}
			for (var y = this.dataset.num*25-24; y < this.dataset.num*25; y++){
				if (pagination__tr[y] != undefined){
					pagination__tr[y].className = "pagination_true";
				}
			}
		}
	}
}

// сортировка таблицы
function sort() {
	var grid = document.getElementById('grid');

	grid.onclick = function(e) {
		if (e.target.tagName != 'TH') return;
		sortGrid(e.target.cellIndex, e.target.getAttribute('data-type'));
	};

	function sortGrid(colNum, type) {
		var tbody = grid.getElementsByTagName('tbody')[0];
		var rowsArray = [].slice.call(tbody.rows);

		var compare;

		switch (type) {
			case 'number':
				compare = function(rowA, rowB) {
					return rowA.cells[colNum].innerHTML - rowB.cells[colNum].innerHTML;
				};
				break;
			case 'string':
				compare = function(rowA, rowB) {
					return rowA.cells[colNum].innerHTML > rowB.cells[colNum].innerHTML;
				};
				break;
		}
		rowsArray.sort(compare);

		grid.removeChild(tbody);

		for (var i = 0; i < rowsArray.length; i++) {
			tbody.appendChild(rowsArray[i]);
		}
		grid.appendChild(tbody);
	}
}

// Ajax отправка фаила
function sendXml(){
	var formData = new FormData(document.forms.person);
	var xhr = new XMLHttpRequest();
	xhr.open("POST", "workWithXml.php", true);
	xhr.send(formData);
	document.getElementById('file').value = '';
	xhr.onreadystatechange = function() {
		if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
			var table_new = this.responseText;
			if (document.getElementById('grid') != null){
				document.getElementById('table_add').innerHTML = '';
				var table_new_split = table_new.split('|-_-|');
				console.log(table_new_split);
				document.getElementById('table_add').innerHTML = table_new_split[0];

				document.getElementsByClassName('report')[0].innerHTML = '';
				document.getElementsByClassName('report')[0].innerHTML = "<span>Удалено: "+table_new_split[1]+"</span><span class='ml-15'>Обновлено: "+table_new_split[2]+"</span><span class='ml-15'>Обработано: "+table_new_split[3]+"</span>";
			} else {
				document.getElementById('table_add').innerHTML = table_new;
			}
			sort();
			pagination();
		}
	}
}
