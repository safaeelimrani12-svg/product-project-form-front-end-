const API_URL = "../produit_controller.php";
const CATEGORIE_API_URL = "../categorie_controller.php";

function loadProduit() {
    // ==========================================
    // READ - Afficher les produits
    // ==========================================

    $.ajax({
        url: API_URL,
        type: "GET",
        dataType: "json",

        success: function(produits){

            let lignes = "";

            $.each(produits, function(index, produit){

                lignes += `
                    <tr>
                        <td>${produit.idPro}</td>
                        <td>
                            <img src="../images/${produit.photo}" class="product-img">
                        </td>
                        <td>${produit.nom}</td>
                        <td>${produit.description}</td>
                        <td>${produit.prix}</td>
                        <td>${produit.quantite}</td>
                        <td>${produit.nomCat}</td>
                        <td class="actions">
                            <button class="delete-btn" data-id="${produit.idPro}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                            <button class="edit-btn" data-id="${produit.idPro}">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            $("#listeProduits").html(lignes);
        },

        error: function () {
            alert("Erreur lors du chargement des produits");
        }

    });
};

// ==========================================
// CATEGORIES
// ==========================================
function loadCategories() {
    $.ajax({
        url: CATEGORIE_API_URL,
        type: "GET",
        dataType: "json",

        success: function(categories) {
            let options = `
                <option value="">
                    -- sélectionner une catégorie --
                </option>
            `;

            $.each(categories, function(index, categorie) {
                options += `
                    <option value="${categorie.idCat}">
                        ${categorie.nomCat}
                    </option>
                `;
            });
            $("#categorie").html(options);
        },

        error: function(xhr) {
            console.log(xhr.responseText);
            alert("Erreur lors du chargement des catégories");
        }
    });
}

// ==========================================
// CREATE / UPDATE
// ==========================================

$("#formProduit").submit(function (event) {
    event.preventDefault();

    const id = $("#idPro").val();
    const produit = {

        nom: $("#nom").val(),
        description: $("#description").val(),
        prix: $("#prix").val(),
        quantite: $("#quantite").val(),
        categorie: $("#categorie").val(),
    };
 
    let method = "POST";
 
    // UPDATE
 
    if (id !== "") {
 
        method = "PUT";
        produit.idPro = id;
 
    }
 
 
    $.ajax({
 
        url: API_URL,
        method: method,
        contentType: "application/json",
        data: JSON.stringify(produit),

        success: function (response) {
            alert(response.message);
            resetForm();
            loadProduit(); 
        },
        error: function (xhr) {
 
            let message = "Une erreur est survenue"; 
            if (xhr.responseJSON) {
                message = xhr.responseJSON.error;
            }
            alert(message);
 
        }
 
    });
 
});

// ==========================================
// EDIT
// ==========================================
$(document).on("click", ".edit-btn", function () {
    const id = $(this).data("id");
    editProduit(id);

});
 
// ==========================================
// EDIT - charger produit dans formulaire
// ==========================================
function editProduit(id) {
 
    $.ajax({
 
        url: API_URL,
        method: "GET",
        dataType: "json",
 
        success: function (produits) {
 
            const produit = produits.find(
                element => element.idPro == id
            );
 
            if (!produit) {
                return;
            }
 
            $("#idPro").val(produit.idPro);
            $("#nom").val(produit.nom);
            $("#description").val(produit.description);
            $("#prix").val(produit.prix);
            $("#quantite").val(produit.quantite);
            $("#categorie").val(produit.idCat);
            $("#btnProduit").text("Modifier le produit");
 
        },
        error: function(xhr) {
            console.log(xhr.responseText);
        }
 
    });
 
}

// ==========================================
// DELETE
// ==========================================

$(document).on("click", ".delete-btn", function() {
    const id = $(this).data("id");

    if (!confirm("Voulez-vous supprimer ce produit ?")) {
        return;
    }

    $.ajax({

        url: API_URL,
        method: "DELETE",
        contentType: "application/json",
        data: JSON.stringify({
            idPro: id
        }),
        dataType: "json",

        success: function(response) {
            alert(response.message);
            loadProduit();
        },

        error: function(xhr) {
            console.log(xhr.responseText);

            let message = "Erreur lors de la suppression";
            if (xhr.responseJSON && xhr.responseJSON.error) {
                message = xhr.responseJSON.error;
            }

            alert(message);
        }

    });

});


// ==========================================
// RESET FORM
// ==========================================

function resetForm() {

    $("#formProduit")[0].reset();

    $("#idPro").val("");

    $("#btnProduit").text("Ajouter le produit");

}


// ==========================================
// Bouton reset
// ==========================================

$("#formProduit").on("reset", function() {

    $("#idPro").val("");

    $("#btnProduit").text("Ajouter le produit");

});


// ==========================================
// Charger les données au démarrage
// ==========================================

loadCategories();

loadProduit();