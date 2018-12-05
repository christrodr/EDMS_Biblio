/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    $('#sectionsList').DataTable({
        "oLanguage": {
            "sUrl": "/js/plugins/dataTable/fr_FR.txt"
        }
    });
    $('#categoriesList').DataTable({
        "oLanguage": {
            "sUrl": "/js/plugins/dataTable/fr_FR.txt"
        }
    });
});

