<?php
// search_bar.php - Component สำหรับแสดง search bar และปุ่ม export/add
?>
<div class="searchsection">
    <input type="text" name="search_bar" id="search_bar" placeholder="ค้นหา..." onkeyup="searchFun()">
    <?php if(isset($show_add_button) && $show_add_button): ?>
    <button class="adduser" id="adduser" onclick="adduseropen()"><i class="fa-solid fa-bookmark"></i> Add</button>
    <?php endif; ?>
    <form action="./exportdata.php" method="post">
        <button class="exportexcel" id="exportexcel" name="exportexcel" type="submit"><i class="fa-solid fa-file-arrow-down"></i></button>
    </form>
</div>

<style>
.searchsection {
    display: flex;
    justify-content: space-between;
    padding: 15px;
    background-color: #f8f9fa;
    margin-bottom: 20px;
    border-radius: 5px;
    z-index: 1;
    position: relative;
}
.searchsection input {
    padding: 8px 15px;
    border-radius: 5px;
    border: 1px solid #ddd;
    width: 300px;
}
.adduser, .exportexcel {
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    color: white;
    cursor: pointer;
}
.adduser {
    background-color: #007bff;
    margin-left: 10px;
}
.exportexcel {
    background-color: #28a745;
}
</style>

<script>
// Search function
function searchFun() {
    let filter = document.getElementById('search_bar').value.toUpperCase();
    let myTable = document.getElementById("table-data");
    let tr = myTable.getElementsByTagName('tr');

    for (var i = 0; i < tr.length; i++) {
        let td = tr[i].getElementsByTagName('td')[1];
        if (td) {
            let textvalue = td.textContent || td.innerHTML;
            if (textvalue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
</script> 