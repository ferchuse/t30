//===========BUSQUEDA DE FILTROS=============
function buscar(filtro,table_id,indice) {
    // Declare variables
    var  filter, table, tr, td, i;
    filter = filtro.toUpperCase();
    table = document.getElementById(table_id);
    tr = table.getElementsByTagName("tr");


    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[indice];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
      tr[i].style.display = "";
      } else {
      tr[i].style.display = "none";
      }
    }
    }
    var num_rows = $(table).find('tbody tr:visible').length;
    return num_rows;
}