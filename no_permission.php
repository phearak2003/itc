<style>
    body {
        background-color: #f8f9fa;
    }

    .main-container {
        margin-top: 50px;
    }

    .no-permission-image {
        max-width: 200px;
        height: auto;
        border-radius: 10px;
    }

    .alert-custom {
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    .btn-custom {
        background-color: #007bff;
        color: white;
        border-radius: 5px;
        padding: 10px 20px;
    }

    .btn-custom:hover {
        background-color: #0056b3;
        color: white;
    }
</style>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6 text-center">
        <img src="uploads/assets/no-permission.png" alt="Forbidden" class="no-permission-image mb-4">

        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <div class="text-center">
                <h5 class="alert-heading fw-bold">Forbidden</h5>
                <p class="mb-1">You do not have the necessary permissions to access this page.</p>
                <hr>
                <p class="mb-0">Please contact your administrator for more details.</p>
            </div>
        </div>

        <a href="dashboard.php" class="btn btn-custom mt-3">Back to Dashboard</a>
    </div>

</div>
</div>