<?php
/*
Plugin Name: Authors Table
Description: Display authors table from external MySQL database.
Version: 1.7.2
Author: Seu Nome
*/

// Shortcode para exibir a tabela de autores
function authors_table_shortcode() {
    ob_start();
    
    // Conectar ao banco de dados externo
    $db_host = 'localhost';
    $db_user = 'USUARIO';
    $db_pass = 'SENHA';
    $db_name = 'DATABASE';

    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Verificar a conexão
    if ($conn->connect_error) {
        die('Erro de conexão com o banco de dados: ' . $conn->connect_error);
    }

    // Consulta SQL para obter os autores
    $query = "SELECT * FROM authors";
    $result = $conn->query($query);

    // Verificar se há resultados
    if ($result->num_rows > 0) {
        echo '<table>';
        echo '<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Birthdate</th><th>Added</th></tr>';
        
        // Loop através dos resultados
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . $row['first_name'] . '</td>';
            echo '<td>' . $row['last_name'] . '</td>';
            echo '<td>' . $row['email'] . '</td>';
            echo '<td>' . $row['birthdate'] . '</td>';
            echo '<td>' . $row['added'] . '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo 'Nenhum autor encontrado.';
    }

    // Fechar a conexão
    $conn->close();

    return ob_get_clean();
}

// Registrar o shortcode
add_shortcode('authors_table', 'authors_table_shortcode');


// Shortcode para exibir a tabela de autores do JSON
function authors_json_table_shortcode() {
    ob_start();

    // Caminho para o arquivo JSON
    $json_file_path = plugin_dir_path(__FILE__) . 'authors.json';

    // Verificar se o arquivo JSON existe
    if (file_exists($json_file_path)) {
        // Ler o conteúdo do arquivo JSON
        $json_content = file_get_contents($json_file_path);

        // Decodificar o conteúdo JSON
        $authors = json_decode($json_content, true);

        // Verificar se há autores no JSON
        if (!empty($authors)) {
            echo '<table>';
            echo '<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Birthdate</th><th>Added</th></tr>';

            // Loop através dos autores
            foreach ($authors as $author) {
                echo '<tr>';
                echo '<td>' . $author['id'] . '</td>';
                echo '<td>' . $author['first_name'] . '</td>';
                echo '<td>' . $author['last_name'] . '</td>';
                echo '<td>' . $author['email'] . '</td>';
                echo '<td>' . $author['birthdate'] . '</td>';
                echo '<td>' . $author['added'] . '</td>';
                echo '</tr>';
            }

            echo '</table>';
        } else {
            echo 'Nenhum autor encontrado no arquivo JSON.';
        }
    } else {
        echo 'O arquivo JSON não foi encontrado.';
    }

    return ob_get_clean();
}

// Registrar o shortcode para o JSON
add_shortcode('authors_json_table', 'authors_json_table_shortcode');

// Shortcode para exibir a tabela de autores do CSV
function authors_csv_table_shortcode() {
    ob_start();

    // Caminho para o arquivo CSV
    $csv_file_path = plugin_dir_path(__FILE__) . 'authors.csv';

    // Verificar se o arquivo CSV existe
    if (file_exists($csv_file_path)) {
        // Ler o conteúdo do arquivo CSV
        $csv_content = file_get_contents($csv_file_path);

        // Converter o conteúdo CSV em um array
        $csv_lines = explode("\n", $csv_content);
        $csv_data = array_map('str_getcsv', $csv_lines);

        // Verificar se há dados no CSV
        if (!empty($csv_data)) {
            echo '<table>';
            echo '<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Birthdate</th><th>Added</th></tr>';

            // Loop através das linhas do CSV
            foreach ($csv_data as $row) {
                echo '<tr>';
                foreach ($row as $cell) {
                    echo '<td>' . $cell . '</td>';
                }
                echo '</tr>';
            }

            echo '</table>';
        } else {
            echo 'Nenhum dado encontrado no arquivo CSV.';
        }
    } else {
        echo 'O arquivo CSV não foi encontrado.';
    }

    return ob_get_clean();
}

// Registrar o shortcode para o CSV
add_shortcode('authors_csv_table', 'authors_csv_table_shortcode');


// Shortcode para exibir a tabela de autores com filtro de e-mail
function authors_table_with_filter_shortcode() {
    ob_start();
    
    // Conectar ao banco de dados externo
    $db_host = 'localhost';
    $db_user = 'USUARIO';
    $db_pass = 'SENHA';
    $db_name = 'DATABASE';

    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Verificar a conexão
    if ($conn->connect_error) {
        die('Erro de conexão com o banco de dados: ' . $conn->connect_error);
    }

    // Verificar se um e-mail foi filtrado
    $filter_email = isset($_GET['filter_email']) ? $_GET['filter_email'] : '';
    $filter_birthdate = isset($_GET['filter_birthdate']) ? $_GET['filter_birthdate'] : '';

    // Consulta SQL para obter os autores com filtro de e-mail
    $query = "SELECT * FROM authors WHERE email LIKE '%$filter_email%' AND birthdate = '{$filter_birthdate}'";
    $result = $conn->query($query);

    // Formulário de filtro de e-mail
    echo '<form method="get" action="">';
    echo '<label for="filter_email">Filtrar por E-mail: </label>';
    echo '<input type="text" name="filter_email" value="' . esc_attr($filter_email) . '">';
    echo '<input type="text" name="filter_birthdate" value="' . esc_attr($filter_birthdate) . '">';
    echo '<input type="submit" value="Filtrar">';
    
    echo '</form>';

    // Verificar se há resultados
    if ($result->num_rows > 0) {
        echo '<table>';
        echo '<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Birthdate</th><th>Added</th></tr>';
        
        // Loop através dos resultados
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . $row['first_name'] . '</td>';
            echo '<td>' . $row['last_name'] . '</td>';
            echo '<td>' . $row['email'] . '</td>';
            echo '<td>' . $row['birthdate'] . '</td>';
            echo '<td>' . $row['added'] . '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo 'Nenhum autor encontrado.';
    }

    // Fechar a conexão
    $conn->close();

    return ob_get_clean();
}

// Registrar o shortcode com filtro de e-mail
add_shortcode('authors_table_with_filter', 'authors_table_with_filter_shortcode');


/****
 * AJAX
 */


// Adicione scripts JS para manipulação AJAX
function authors_table_ajax_scripts() {
    wp_enqueue_script('authors-table-ajax-script', plugin_dir_url(__FILE__) . 'authors-table-ajax.js', array('jquery'), '1.0', true);

    // Passar a URL da admin-ajax.php para o script JS
    wp_localize_script('authors-table-ajax-script', 'authors_table_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}

add_action('wp_enqueue_scripts', 'authors_table_ajax_scripts');

// Adicione esta função para enfileirar o estilo CSS
function enqueue_authors_table_styles() {
    wp_enqueue_style('authors-table-styles', plugin_dir_url(__FILE__) . 'authors-table-style.css');
}

// Adicione esta ação para chamar a função durante o carregamento de scripts e estilos do WordPress
add_action('wp_enqueue_scripts', 'enqueue_authors_table_styles');


/*// Shortcode para exibir a tabela de autores com filtro de e-mail usando AJAX
function authors_table_ajax_shortcode() {
    ob_start();
    
    // Formulário de filtro de e-mail
    echo '<form id="authors-email-filter-form">';
    echo '<label for="filter_email">Filtrar por E-mail: </label>';
    echo '<input type="text" id="filter_email" name="filter_email">';
    echo '<input type="button" value="Filtrar" id="filter_button">';
    echo wp_nonce_field('authors_table_nonce', 'security', true, false);
    echo '</form>';

    // Div para exibir a tabela
    echo '<div id="authors-table-container">';
    
    // Consulta inicial para exibir todos os registros
    $all_authors = get_all_authors();
    display_authors_table($all_authors);

    echo '</div>';

    return ob_get_clean();
}*/

// Shortcode para exibir a tabela de autores com filtro de e-mail usando AJAX
function authors_table_ajax_shortcode() {
    ob_start();
    
    // Formulário de filtro de e-mail
    echo '<form id="authors-email-filter-form">';
    echo '<label for="filter_email">Filtrar por E-mail: </label>';
    echo '<input type="text" id="filter_email" name="filter_email">';
    echo '<input type="button" value="Filtrar" id="filter_button">';
    echo wp_nonce_field('authors_table_nonce', 'security', true, false);
    echo '</form>';

    // Formulário de adição/editar autor
    echo '<form id="author-form" style="display: none;">';
    echo '<input type="hidden" id="author-id" name="author-id">';
    echo '<label for="edit_first_name">First Name: </label>';
    echo '<input type="text" id="edit_first_name" name="edit_first_name"><br>';
    echo '<label for="edit_last_name">Last Name: </label>';
    echo '<input type="text" id="edit_last_name" name="edit_last_name"><br>';
    echo '<label for="edit_email">Email: </label>';
    echo '<input type="text" id="edit_email" name="edit_email"><br>';
    echo '<label for="edit_birthdate">Birthdate: </label>';
    echo '<input type="text" id="edit_birthdate" name="edit_birthdate"><br>';
    echo '<input type="button" value="Salvar Alterações" id="save-changes-button">';
    echo '<input type="button" value="Cancelar" id="cancel-button">';
    echo '</form>';

     // Botão para adicionar novo autor
     echo '<button id="add-author-button">Adicionar Novo Autor</button>';

    // Div para exibir a tabela
    // Div para exibir a tabela
    echo '<div id="authors-table-container">';
    
    // Consulta inicial para exibir todos os registros
    $all_authors = get_all_authors();
    display_authors_table($all_authors);

    echo '</div>';

   

    return ob_get_clean();
}

// Restante do código


// Função para obter todos os autores
function get_all_authors() {
    // Conectar ao banco de dados externo
    $db_host = 'localhost';
    $db_user = 'USUARIO';
    $db_pass = 'SENHA';
    $db_name = 'DATABASE';

    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Verificar a conexão
    if ($conn->connect_error) {
        die(json_encode(array('error' => 'Erro de conexão com o banco de dados: ' . $conn->connect_error)));
    }

    // Consulta SQL para obter todos os autores
    $query = "SELECT * FROM authors";
    $result = $conn->query($query);

    // Obter todos os autores como um array
    $all_authors = array();
    while ($row = $result->fetch_assoc()) {
        $all_authors[] = $row;
    }

    // Fechar a conexão
    $conn->close();

    return $all_authors;
}

// Função para exibir a tabela de autores
function display_authors_table($authors) {
    echo '<table>';
    echo '<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Birthdate</th><th>Added</th><th>Actions</th></tr>';

    // Loop através dos resultados
    foreach ($authors as $author) {
        echo '<tr>';
        echo '<td>' . $author['id'] . '</td>';
        echo '<td>' . $author['first_name'] . '</td>';
        echo '<td>' . $author['last_name'] . '</td>';
        echo '<td>' . $author['email'] . '</td>';
        echo '<td>' . $author['birthdate'] . '</td>';
        echo '<td>' . $author['added'] . '</td>';
        echo '<td><button class="edit-author" data-id="' . $author['id'] . '">Editar</button> <button class="delete-author" data-id="' . $author['id'] . '">Excluir</button></td>';
        echo '</tr>';
    }

    echo '</table>';
}

// Registrar o shortcode com filtro de e-mail usando AJAX
add_shortcode('authors_table_ajax', 'authors_table_ajax_shortcode');

// Função AJAX para obter dados do autor para edição
function get_author_data_callback() {
    // Verificar a ação e o nonce
    check_ajax_referer('authors_table_nonce', 'security');

    // Conectar ao banco de dados externo
    $db_host = 'localhost';
    $db_user = 'USUARIO';
    $db_pass = 'SENHA';
    $db_name = 'DATABASE';

    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Verificar a conexão
    if ($conn->connect_error) {
        die(json_encode(array('error' => 'Erro de conexão com o banco de dados: ' . $conn->connect_error)));
    }

    // Obter o ID do autor
    $author_id = isset($_POST['author_id']) ? $_POST['author_id'] : '';

    // Consulta SQL para obter os dados do autor
    $query = "SELECT * FROM authors WHERE id = '$author_id'";
    $result = $conn->query($query);

    // Verificar se há resultados
    if ($result->num_rows > 0) {
        $author_data = $result->fetch_assoc();
        echo json_encode(array('author_data' => $author_data));
    } else {
        echo json_encode(array('error' => 'Autor não encontrado.'));
    }

    // Fechar a conexão
    $conn->close();

    die();
}

// Registrar a função AJAX para obter dados do autor
add_action('wp_ajax_get_author_data', 'get_author_data_callback');

// Função AJAX para excluir um autor
function delete_author_callback() {
    // Verificar a ação e o nonce
    check_ajax_referer('authors_table_nonce', 'security');

    // Conectar ao banco de dados externo
    $db_host = 'localhost';
    $db_user = 'USUARIO';
    $db_pass = 'SENHA';
    $db_name = 'DATABASE';

    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Verificar a conexão
    if ($conn->connect_error) {
        die(json_encode(array('error' => 'Erro de conexão com o banco de dados: ' . $conn->connect_error)));
    }

    // Obter o ID do autor
    $author_id = isset($_POST['author_id']) ? $_POST['author_id'] : '';

    // Consulta SQL para excluir o autor
    $query = "DELETE FROM authors WHERE id = '$author_id'";
    $result = $conn->query($query);

    // Verificar se a exclusão foi bem-sucedida
    if ($result) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('error' => 'Erro ao excluir o autor.'));
    }

    // Fechar a conexão
    $conn->close();

    die();
}

// Registrar a função AJAX para excluir um autor
add_action('wp_ajax_delete_author', 'delete_author_callback');


// Função AJAX para obter dados filtrados
function authors_table_ajax_callback() {
    // Verificar a ação e o nonce
    check_ajax_referer('authors_table_nonce', 'security');

    // Conectar ao banco de dados externo
    $db_host = 'localhost';
    $db_user = 'USUARIO';
    $db_pass = 'SENHA';
    $db_name = 'DATABASE';

    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Verificar a conexão
    if ($conn->connect_error) {
        die(json_encode(array('error' => 'Erro de conexão com o banco de dados: ' . $conn->connect_error)));
    }

    // Obter o e-mail filtrado
    $filter_email = isset($_POST['filter_email']) ? $_POST['filter_email'] : '';

    // Consulta SQL para obter os autores com filtro de e-mail
    $query = "SELECT * FROM authors WHERE email LIKE '%$filter_email%'";
    $result = $conn->query($query);

    // Construir a tabela HTML
    $table_html = '<table>';
    $table_html .= '<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Birthdate</th><th>Added</th><th>Actions</th></tr>';

    // Loop através dos resultados
    while ($row = $result->fetch_assoc()) {
        $table_html .= '<tr>';
        $table_html .= '<td>' . $row['id'] . '</td>';
        $table_html .= '<td>' . $row['first_name'] . '</td>';
        $table_html .= '<td>' . $row['last_name'] . '</td>';
        $table_html .= '<td>' . $row['email'] . '</td>';
        $table_html .= '<td>' . $row['birthdate'] . '</td>';
        $table_html .= '<td>' . $row['added'] . '</td>';
        $table_html .= '<td><button class="edit-author" data-id="' . $row['id'] . '">Editar</button> <button class="delete-author" data-id="' . $row['id'] . '">Excluir</button></td>';
        $table_html .= '</tr>';
    }

    $table_html .= '</table>';

    // Fechar a conexão
    $conn->close();

    // Enviar a tabela HTML de volta para o script AJAX
    echo json_encode(array('table_html' => $table_html));

    die();
}

// Registrar a função AJAX
add_action('wp_ajax_authors_table_ajax_filter', 'authors_table_ajax_callback');

// Permitir CORS para solicitações AJAX
add_action('init', 'allow_cors_ajax');
function allow_cors_ajax() {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET");
    header("Access-Control-Allow-Credentials: true");
}

// Função AJAX para atualizar um autor
// Atualizada função para lidar com a adição de novos autores
function update_author_callback() {
    // Verificar a ação e o nonce
    check_ajax_referer('authors_table_nonce', 'security');

    // Conectar ao banco de dados externo
    $db_host = 'localhost';
    $db_user = 'USUARIO';
    $db_pass = 'SENHA';
    $db_name = 'DATABASE';

    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Verificar a conexão
    if ($conn->connect_error) {
        die(json_encode(array('error' => 'Erro de conexão com o banco de dados: ' . $conn->connect_error)));
    }

    // Obter os dados do autor para atualização ou inserção
    $author_id = isset($_POST['author_id']) ? intval($_POST['author_id']) : 0;
    $edit_first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
    $edit_last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
    $edit_email = isset($_POST['email']) ? $_POST['email'] : '';
    $edit_birthdate = isset($_POST['birthdate']) ? $_POST['birthdate'] : '';

    // Se não houver ID do autor, é uma inserção (INSERT)
    if ($author_id === 0) {
        // Consulta SQL para inserir um novo autor
        $query = "INSERT INTO authors (first_name, last_name, email, birthdate) VALUES ('$edit_first_name', '$edit_last_name', '$edit_email', '$edit_birthdate')";

        $result = $conn->query($query);

        // Verificar se a inserção foi bem-sucedida
        if ($result) {
            echo json_encode(array('success' => true, 'message' => 'Autor adicionado com sucesso.'));
        } else {
            echo json_encode(array('error' => 'Erro ao adicionar o autor.'));
        }
    } else {
        // Se houver um ID do autor, é uma atualização (UPDATE)
        // Consulta SQL para atualizar o autor
        $query = "UPDATE authors SET
                  first_name = '$edit_first_name',
                  last_name = '$edit_last_name',
                  email = '$edit_email',
                  birthdate = '$edit_birthdate'
                  WHERE id = '$author_id'";

        $result = $conn->query($query);

        // Verificar se a atualização foi bem-sucedida
        if ($result) {
            echo json_encode(array('success' => true, 'message' => 'Autor atualizado com sucesso.'));
        } else {
            echo json_encode(array('error' => 'Erro ao atualizar o autor.'));
        }
    }

    // Fechar a conexão
    $conn->close();

    die();
}


// Registrar a função AJAX para atualizar um autor
add_action('wp_ajax_update_author', 'update_author_callback');


// Adicione esta ação para lidar com as alterações do autor
add_action('wp_ajax_save_author_changes', 'save_author_changes_callback');

// Função para processar as alterações do autor
function save_author_changes_callback() {
    // Verificar a ação e o nonce
    check_ajax_referer('authors_table_nonce', 'security');

    // Obter dados do formulário
    $author_id = isset($_POST['author_id']) ? intval($_POST['author_id']) : 0;
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $email = sanitize_email($_POST['email']);
    $birthdate = sanitize_text_field($_POST['birthdate']);

    // Lógica para salvar as alterações ou adicionar novo autor
    // ...

    // Responder à requisição AJAX
    wp_send_json_success('Changes saved successfully.');
}


