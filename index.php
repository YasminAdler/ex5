<!DOCTYPE html>
<html>

<head>
    <title>Yas's library</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="stylesheet.css">
    <script src="getCategory.js"></script>

</head>

<body>
    <div class="container">
        <?php
        // Database connection configuration
        include "config.php";

        // Create connection
        $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Retrieve list of categories from JSON file
        $categoryData = file_get_contents('categories.json');
        $categories = json_decode($categoryData, true);


        // Function to retrieve all books
        function getAllBooks($conn)
        {
            $sql = "SELECT * FROM tbl_15_books";
            $result = $conn->query($sql);

            $books = array();

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $books[] = $row;
                }
            }

            return $books;
        }

        // Function to retrieve books by category
        function getBooksByCategory($conn, $category)
        {
            if ($category == "All Categories") {
                return getAllBooks($conn);
            } else {
                $sql = "SELECT * FROM tbl_15_books WHERE category = '$category'";
                $result = $conn->query($sql);

                $books = array();

                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $books[] = $row;
                    }
                }

                return $books;
            }
        }

        // Check if a book ID is provided and display its details
        if (isset($_GET['id'])) {
            $bookId = $_GET['id'];

            $sql = "SELECT * FROM tbl_15_books WHERE id = $bookId";
            $result = $conn->query($sql);

            if ($result) {
                $book = $result->fetch_assoc();

                // Display book details
                echo "<h2>{$book['Name']}</h2>";
                echo "<p>{$book['description']}</p>";
                echo "<p>Category: {$book['category']}</p>";
                echo '<div class="book-details">';
        
                $img = $book['path'];
                $img2 = $book['path2'];

                if (!$img)
                    $img = "images/default.jpg";
                echo '<img src="' . $img . '">';
                echo '<img src="' . $img2 . '">';
                echo '</div>';
                echo "<p class='text-center'>Price: {$book['price']} </p>";
                
            } else {
                echo "Book not found.";
            }

            // Close the connection and stop further execution
            $conn->close();
            exit;
        }

        // Check if a category is selected and retrieve books for that category
        if (isset($_POST['category'])) {
            $selectedCategory = $_POST['category'];
            $books = getBooksByCategory($conn, $selectedCategory);
        } else {
            // Retrieve all books if no category is chosen
            $books = getAllBooks($conn);
        }
        
        ?>

        <h2>Books List</h2>

        <form method="POST" action="">
            <div class="form-group">
                <select class="form-control" name="category">
                    <option value="All Categories">All Categories</option>
                    <?php
                    foreach ($categories as $category) {
                        $selected = (isset($selectedCategory) && $selectedCategory == $category) ? "selected" : "";
                        echo "<option value='$category' $selected>$category</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>

        <div class="row">
            <br>
            <?php
            foreach ($books as $book) {
                echo '<div class="col-md-4">';
                echo '<div class="card">';
                $img = $book['path'];

                if (!$img)
                    $img = "images/default.jpg";
                echo '<img class="card-img-top" src="' . $img . '" alt="Book Image">';
                echo '<div class="card-body">';
                echo "<h5 class='card-title'><a href='?id={$book['id']}'>{$book['Name']}</a></h5>";
                echo "<p class='card-text'>{$book['description']}</p>";
                echo "<p class='card-text'>Price: {$book['price']}</p>";
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>

        <?php
        // Close the database connection
        $conn->close();
        ?>
    </div>
</body>

</html>
