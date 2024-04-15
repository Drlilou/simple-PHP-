function openModal() {
    document.getElementById('myModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('myModal').style.display = 'none';
}


function deleteFile(id) {
    // Ask for confirmation before deleting
    if (confirm("Are you sure you want to delete this file?")) {
        // If user confirms, proceed with deletion
        // You can make an AJAX request to the server to delete the file by its ID
        // Here, I'm assuming you have a PHP script named delete_file.php to handle the deletion
        // You can pass the file ID to the server-side script to identify which file to delete
        fetch('index.php?id=' + id, {
            method: 'DELETE'
        })
        .then(response => {
            if (response.ok) {
                // File deleted successfully
                // You can update the UI to reflect the changes (e.g., remove the row from the table)
                document.getElementById('row_' + id).remove(); // Assuming each row has an ID like 'row_1', 'row_2', etc.
            } else {
                // Error occurred while deleting the file
                console.error('Error deleting file:', response.statusText);
            }
        })
        .catch(error => {
            console.error('Error deleting file:', error);
        });
    } else {
        // If user cancels the deletion, do nothing
        console.log("Deletion cancelled.");
    }
}
