jQuery(document).ready(function ($) {
  // Restante do código

  // Adicione este trecho para lidar com a edição
  $(".edit-author").click(function () {
    var authorId = $(this).data("id");
    //var security = $('#authors-email-filter-form input[name="security"]').val();
    var security = $("#security").val();

    $.ajax({
      type: "POST",
      url: authors_table_ajax_object.ajax_url,
      data: {
        action: "get_author_data",
        author_id: authorId,
        security: security,
      },
      success: function (response) {
        var result = $.parseJSON(response);
        showEditForm(result.author_data);
      },
      error: function (error) {
        console.log(error);
      },
    });
  });

  // Função para exibir o formulário de edição
  function showEditForm(authorData) {
    // Preencha o formulário com os dados do autor
    $("#author-id").val(authorData.id);
    $("#edit_first_name").val(authorData.first_name);
    $("#edit_last_name").val(authorData.last_name);
    $("#edit_email").val(authorData.email);
    $("#edit_birthdate").val(authorData.birthdate);

    // Exibir o formulário de edição
    $("#author-form").show();
  }

  // Adicione um botão para mostrar o formulário de adição
  $("#add-author-button").click(function () {
    // Limpe os campos do formulário antes de mostrar
    $("#author-id").val("");
    $("#edit_first_name").val("");
    $("#edit_last_name").val("");
    $("#edit_email").val("");
    $("#edit_birthdate").val("");

    // Exibir o formulário de adição
    $("#author-form").show();
  });

  // Adicione um botão para cancelar a edição/adicionar
  $("#cancel-button").click(function () {
    // Ocultar o formulário
    $("#author-form").hide();
  });

  // Adicione um botão para salvar as alterações ou adicionar novo autor
  $("#save-changes-button").click(function () {
    // Lógica para salvar as alterações ou adicionar novo autor
    saveChanges();
  });

  // Função para salvar as alterações
  /*function saveChanges() {
    var authorId = $('#editAuthorForm input[name="author_id"]').val();
    var editFirstName = $(
      '#editAuthorForm input[name="edit_first_name"]'
    ).val();
    var editLastName = $('#editAuthorForm input[name="edit_last_name"]').val();
    var editEmail = $('#editAuthorForm input[name="edit_email"]').val();
    var editBirthdate = $('#editAuthorForm input[name="edit_birthdate"]').val();
    var security = $('#authors-email-filter-form input[name="security"]').val();

    $.ajax({
      type: "POST",
      url: authors_table_ajax_object.ajax_url,
      data: {
        action: "update_author",
        author_id: authorId,
        first_name: editFirstName,
        last_name: editLastName,
        email: editEmail,
        birthdate: editBirthdate,
        security: security,
      },
      success: function (response) {
        var result = $.parseJSON(response);
        if (result.success) {
          // Fechar o modal após salvar as alterações
          //$("#editAuthorModal").modal("hide");
          $("#author-form").hide();

          // Recarregar a tabela após a edição
          var filterEmail = $("#filter_email").val();
          filterAuthors(filterEmail);
        } else {
          console.log(result.error);
        }
      },
      error: function (error) {
        console.log(error);
      },
    });
  }*/

  // Função para salvar alterações ou adicionar novo autor
function saveChanges() {
    // Obter dados do formulário
    var authorId = $('#author-id').val();
    var firstName = $('#edit_first_name').val();
    var lastName = $('#edit_last_name').val();
    var email = $('#edit_email').val();
    var birthdate = $('#edit_birthdate').val();
    var security = $('#authors-email-filter-form input[name="security"]').val();

    // Construir os dados para a requisição AJAX
    var data = {
        action: 'update_author',
        author_id: authorId,
        first_name: firstName,
        last_name: lastName,
        email: email,
        birthdate: birthdate,
        security: security
    };

    // Realizar a requisição AJAX
    $.ajax({
        type: 'POST',
        url: authors_table_ajax_object.ajax_url,
        data: data,
        success: function(response) {
            var result = $.parseJSON(response);
            if (result.success) {
            // Fechar o modal após salvar as alterações
            //$("#editAuthorModal").modal("hide");
            $("#author-form").hide();

            // Recarregar a tabela após a edição
            var filterEmail = $("#filter_email").val();
            filterAuthors(filterEmail);
            } else {
            console.log(result.error);
            }
        },
        error: function(error) {
            // Lógica para lidar com erros, se necessário
            console.log(error);
        }
    });

    // Limpar e ocultar o formulário após o envio
    $('#author-id').val('');
    $('#edit_first_name').val('');
    $('#edit_last_name').val('');
    $('#edit_email').val('');
    $('#edit_birthdate').val('');
    $('#author-form').hide();
}


  // Adicione este trecho para lidar com a exclusão
  $(".delete-author").click(function () {
    var authorId = $(this).data("id");
    //var security = $('#authors-email-filter-form input[name="security"]').val();
    var security = $("#security").val();

    $.ajax({
      type: "POST",
      url: authors_table_ajax_object.ajax_url,
      data: {
        action: "delete_author",
        author_id: authorId,
        security: security,
      },
      success: function (response) {
        var result = $.parseJSON(response);
        if (result.success) {
          // Recarregar a tabela após a exclusão
          var filterEmail = $("#filter_email").val();
          filterAuthors(filterEmail);
        } else {
          console.log(result.error);
        }
      },
      error: function (error) {
        console.log(error);
      },
    });
  });

  // Função para recarregar a tabela após exclusão
  function filterAuthors(filterEmail) {
    //var security = $('#authors-email-filter-form input[name="security"]').val();
    var security = $("#security").val();

    $.ajax({
      type: "POST",
      url: authors_table_ajax_object.ajax_url,
      data: {
        action: "authors_table_ajax_filter",
        filter_email: filterEmail,
        security: security,
      },
      success: function (response) {
        var result = $.parseJSON(response);
        $("#authors-table-container").html(result.table_html);
      },
      error: function (error) {
        console.log(error);
      },
    });
  }
});

