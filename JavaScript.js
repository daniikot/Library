window.onload = function () {

    var sortByName=document.getElementById("NameBook");
    var sortByYaer = document.getElementById("YearBook");
    var page=0;

    sortByName.addEventListener("click", function(){
        sortByYaer.value=0;
        let form_data;
        if (sortByName.value==0){
            form_data="Sort="+sortByName.value+"&By=Name&Page="+page;
            sortByName.value=1;
        }
        else{
            form_data="Sort="+sortByName.value+"&By=Name&Page="+page;
            sortByName.value=0;
        }
        $.ajax({
            type: "GET",
            url: "view.php",
            data: form_data,
            success: function (data) {
                deleteRow();
                $("#caption").after(data.substr(576));
            }
        })

    })

    
    sortByYaer.addEventListener("click", function(){
        sortByName.value=0;
        let form_data;
        if (sortByYaer.value==0){
            form_data="Sort="+(Number(sortByYaer.value)+3)+"&By=Year&Page="+page;
            sortByYaer.value=1;
        }
        else{
            form_data="Sort="+(Number(sortByYaer.value)+3)+"&By=Year&Page="+page;
            sortByYaer.value=0;
        }
        $.ajax({
            type: "GET",
            url: "view.php",
            data: form_data,
            success: function (data) {
                deleteRow();
                $("#caption").after(data.substr(576));
            }
        })
    })
    
    $("#table").before("<div><form class=\"form\" id=\"form\"><label>Фильтр по автору <input name=\"Author\" id=\"inputAuthor\"></label><label>Фильтр по Жанру <input name=\"Genre\" id=\"inputGenre\"></label><label>Фильтр по году издания <input name=\"Year\" id=\"inputYear\"></label><button  type=\"submit\">Фильтр</button></form><button id=\"resetFiltr\">Сброс фильтра</button></div>");
    $("#table").after("<div id=\"buttonScroll\"> <button id=\"Left\"> << </button ><button id=\"Right\"> >> </button><label id=\"Page\" for=\"Left\">"+(page+1)+" Страница"+"</label></div>");
    $("#buttonScroll").after("<div><form class='formAddBook' id='formAddBook'><fieldset><legend>Добавить книгу</legend><label>Название книги<input name='addName'></label><label>Жанр книги<input name='addGenre'></label><label>Год издания книги<input name='addYear'></label><label>Автор(ы) книги<input name='addAuthor'></label><button type='submit'>Добавить</button></fieldset></form></div>");
    let leftButton=document.getElementById("Left");
    let rightButton=document.getElementById("Right");
    
    leftButton.addEventListener("click", function(){
        if (page==0){
            alert("Вы и так на первой странице");
        }
        else{
            page--;
            let form_data="By=Page&Page="+page;
            $.ajax({
                type: "GET",
                url: "view.php",
                data: form_data,
                success: function (data) {
                    deleteRow();
                    $("#caption").after(data.substr(576));
                    
                }
            })
            sortByYaer.value=0;
            sortByName.value=0;
            $("#Page").html((page+1)+" Страница");
        }
    })

    rightButton.addEventListener("click", function(){
        page++;
        let form_data="By=Page&Page="+page;
        $.ajax({
            type: "GET",
            url: "view.php",
            data: form_data,
            success: function (data) {
                let end=data.substr(576);
                if(!(end=="")){
                    deleteRow();
                    $("#caption").after(end);
                    sortByYaer.value=0;
                    sortByName.value=0;
                    $("#Page").html((page+1)+" Страница");
                }
                else{
                    alert('Вы на последней странице');
                    page--;
                }

            }
        })

    })


    let resetButton=document.getElementById("resetFiltr");
    resetButton.addEventListener("click", function(){
        let form_data="By=Reset";
        $.ajax({
            type: "GET",
            url: "view.php",
            data: form_data,
            success: function (data) {
                deleteRow();
                $("#caption").after(data.substr(576));
                page=0;
            }
        })
    })


    $("#form").submit(function (e) {
        e.preventDefault();
        var form_data = $(this).serialize()+"&By=Filter";
        $.ajax({
            type: "GET",
            url: "view.php",
            data: form_data,
            success: function (data) {
                deleteRow();
                $("#caption").after(data.substr(576));
                page=0;
            }
        })
        sortByYaer.value=0;
        sortByName.value=0;
    })


    $("#formAddBook").submit(function (e) {
        e.preventDefault();
        var form_data = $(this).serialize()+"&By=Add";
        $.ajax({
            type: "GET",
            url: "view.php",
            data: form_data,
            success: function (data) {
                alert("Книга добавлена!");
            }
        })
    })





}

function deleteRow(){
    let table=document.getElementById("table");
    while (table.rows.length>1) {
        table.deleteRow(1);
    }
}
