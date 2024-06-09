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
                <!--
                <div class="form-group mx-sm-3 mb-2">
                    <label for="sort" class="mr-2">Sort By:</label>
                    <select class="form-control" id="sort" name="sort">
                        <option value="title_asc">Title (A-Z)</option>
                        <option value="title_desc">Title (Z-A)</option>
                        <option value="date_asc">Oldest First</option>
                        <option value="date_desc">Newest First</option>
                    </select>
                </div>
                -->
                <button type="submit" class="btn btn-primary mb-2">Search</button>
            </form>
        </div>
    </div>
    <div class="row">
    <?php
    // Import the Generator class
    use SimpleSoftwareIO\QrCode\Generator;

    // Assuming the QR Code Generator is already autoloaded via Composer
    
    // Loop through each search result
    foreach ($searchResults as $result): ?>
        <div class="col-md-3">
            <div class="card">
                <?php
                // Generate QR code URL for the tracking number
                $trackingNumber = urlencode($result['tracking_number']);
                $qrcode = new Generator;
                $url = base_url("/track?number=$trackingNumber");
                $qrCodeURL = $qrcode->size(200)->generate($url);
                ?>

                <div class="card-body">
                    <div class="d-flex justify-content-center align-items-center mb-2">
                        <?= $qrCodeURL ?>
                    </div>
                    <h5 class="card-title"><?= $result['tracking_number'] ?> (<?= $result['version_number'] ?>)</h5>
                    <p class="card-text"><?= $result['title'] ?> ( v<?= $result['version_number'] ?>)</p>
                    <button class="btn btn-primary" onclick="printQR(`<?= htmlspecialchars($qrCodeURL) ?>`, '<?= esc($result['tracking_number']) ?>')">Print</button>
                    <button class="btn btn-primary"
                        onclick="copyToClipboard('<?= $result['tracking_number'] ?>')">Copy</button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>


</div>

<script>
    function printQR(qrCodeURL, trackingNumber) {
        var printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Print QR Code</title></head><body style="width:100%;text-align:center;"><img src="' + qrCodeURL + '"><div>' + trackingNumber + '</div></body></html>');
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