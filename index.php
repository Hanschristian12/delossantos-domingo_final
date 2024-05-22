<?php
// Define the inventory with items and initial reviews
$inventory = [
    1 => ['name' => 'Item 1', 'category' => 'Electronics', 'brand' => 'Brand A', 'price' => 100.00, 'weight' => 2.0, 'quantity' => 50, 'reviews' => [
        ['user' => 'Alice', 'rating' => 4, 'review' => 'Good product!'],
        ['user' => 'Bob', 'rating' => 5, 'review' => 'Excellent!']
    ]],
    2 => ['name' => 'Item 2', 'category' => 'Home Appliances', 'brand' => 'Brand B', 'price' => 200.00, 'weight' => 3.0, 'quantity' => 30, 'reviews' => [
        ['user' => 'Charlie', 'rating' => 3, 'review' => 'Average.']
    ]],
    // Add other items similarly
];

// Function to get validated input from the user
function getValidatedInput($prompt, $validations) {
    do {
        echo $prompt;
        $input = trim(fgets(STDIN)); // Read user input
        foreach ($validations as $validation) {
            if (!$validation($input)) {
                echo "Invalid input. Please try again.\n";
                continue 2; // Continue with the outer loop if validation fails
            }
        }
        return $input; // Return the validated input
    } while (true);
}

// Function to display the inventory
function displayInventory($inventory) {
    echo "Available items:\n";
    foreach ($inventory as $itemNumber => $item) {
        echo "$itemNumber. {$item['name']} (Category: {$item['category']}, Brand: {$item['brand']}) - {$item['price']} pesos each, {$item['weight']} kg, Quantity: {$item['quantity']}\n";
    }
}

// Function to display reviews for a selected product
function displayReviews($item) {
    if (empty($item['reviews'])) {
        echo "No reviews available for this product.\n";
    } else {
        $totalRating = 0;
        echo "Reviews for {$item['name']}:\n";
        foreach ($item['reviews'] as $review) {
            echo "- User: {$review['user']}, Rating: {$review['rating']}/5, Review: {$review['review']}\n";
            $totalRating += $review['rating'];
        }
        $averageRating = $totalRating / count($item['reviews']);
        echo "Average Rating: " . number_format($averageRating, 2) . "/5\n";
    }
}

// Start of the program

// Display the inventory to the user
displayInventory($inventory);

// Prompt user to select a product
$itemNumber = getValidatedInput("Enter item number to view details and reviews: ", [
    function($input) use ($inventory) { return isset($inventory[$input]); }
]);

// Display details and reviews for the selected product
$selectedItem = $inventory[$itemNumber];
echo "Details for {$selectedItem['name']} (Category: {$selectedItem['category']}, Brand: {$selectedItem['brand']}):\n";
echo "Price: {$selectedItem['price']} pesos, Weight: {$selectedItem['weight']} kg, Quantity: {$selectedItem['quantity']}\n";
displayReviews($selectedItem);

// Allow user to add a new review
$newReview = [];
$newReview['user'] = getValidatedInput("Enter your name: ", [
    function($input) { return !empty($input); }
]);
$newReview['rating'] = getValidatedInput("Enter rating (0-5): ", [
    function($input) { return is_numeric($input) && $input >= 0 && $input <= 5; }
]);
echo "Enter your review: ";
$newReview['review'] = trim(fgets(STDIN)); // Read user input

// Add new review to the product's reviews
$inventory[$itemNumber]['reviews'][] = $newReview;

// Display updated reviews and average rating
displayReviews($inventory[$itemNumber]);

echo "Thank you for your review!\n";
?>

