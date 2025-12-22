<div class="content-wrapper">
    <div class="container-fluid">
        
        <div class="card map-card">
            <div class="card-header">
                <h5 class="mb-0">Dormitory Locations</h5>
            </div>
            <div class="card-body p-0"> <div class="map-container" id="map-frame">
                    <?php include('../../backend/geolocation2.php'); ?>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    
.content-wrapper {
    padding: 20px;
    height: calc(100vh - 60px); /* 60px is the estimated height of your Top Bar */
    overflow: hidden; 
}

.map-card {
    height: 100%;
    display: flex;
    flex-direction: column;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    border-radius: 8px;
    overflow: hidden;
}

.map-card .card-body {
    flex-grow: 1; 
    position: relative;
}

.map-container {
    height: 100%;
    width: 100%;
    min-height: 400px; /* Safety fallback */
}
</style>