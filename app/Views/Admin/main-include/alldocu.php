<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <form action="<?= base_url('search') ?>" method="GET" class="form-inline">
                <div class="form-group mx-sm-3 mb-2">
                    <label for="search" class="sr-only">Search</label>
                    <input type="text" class="form-control" id="search" name="search" placeholder="Search">
                </div>
                <div class="form-group mx-sm-3 mb-2">
                    <label for="office" class="sr-only">Office</label>
                    <select class="form-control" id="office" name="office">
                        <option value="">All Offices</option>
                        <?php foreach ($offices as $office): ?>
                            <option value="<?= $office['office_id'] ?>"><?= $office['office_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group mx-sm-3 mb-2">
                    <label for="status" class="mr-2">Status:</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">All</option>
                        <option value="pending">Pending</option>
                        <option value="received">Received</option>
                        <option value="onprocess">On Process</option>
                        <option value="completed">Completed</option>
                        <option value="deleted">Deleted</option>
                    </select>
                </div>
                <div class="form-group mx-sm-3 mb-2">
                    <label for="sort" class="mr-2">Sort By:</label>
                    <select class="form-control" id="sort" name="sort">
                        <option value="title_asc">Title (A-Z)</option>
                        <option value="title_desc">Title (Z-A)</option>
                        <option value="date_asc">Oldest First</option>
                        <option value="date_desc">Newest First</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary mb-2">Search</button>
            </form>
        </div>
    </div>
    <div class="row">
    <?php foreach ($searchResults as $result): ?>
    <div class="col-md-3">
        <div class="card">
            <img src="<?= base_url('path_to_qr_code_image.jpg') ?>" class="card-img-top" alt="QR Code">
            <div class="card-body">
                <h5 class="card-title"><?= $result['tracking_number'] ?></h5>
                <p class="card-text"><?= $result['title'] ?></p>
                <button class="btn btn-primary" onclick="printQR('<?= base_url('path_to_qr_code_image.jpg') ?>')">Print</button>
                <button class="btn btn-primary" onclick="copyToClipboard('<?= $result['tracking_number'] ?>')">Copy</button>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

</div>

<script>
    function printQR(imageUrl) {
        var printWindow = window.open('', '_blank');
        printWindow.document.write('<img src="' + imageUrl + '" style="width:100%;">');
        printWindow.document.close();
        printWindow.print();
    }

    function copyToClipboard(text) {
        var tempInput = document.createElement("input");
        tempInput.value = text;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
    }
</script>
