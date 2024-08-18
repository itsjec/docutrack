<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Tracking</title>
    <style>
        .hero {
            position: relative;
            color: white;
            padding: 60px 0;
            text-align: center;
            overflow: hidden;
            background-color: #6C007C;
            width: 100%;
            height: 300px;
        }

        .hero-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .form-group label {
            color: #fff;
        }


        .card {
            margin-bottom: 20px;
        }


        .heading {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }


    </style>
</head>
<div class="content-wrapper">
    <div id="particles-js" class="hero">
        <div class="hero-slide"></div>
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-9 text-center">
                    <h3 class="heading" data-aos="fade-up">
                        Effortlessly Track Your Documents Online!
                    </h3>
                    <p style="color: white;">Simply Search and Scan the QR Code Below:</p>
                    <div class="row">
                        <div class="col-12">
                            <form action="<?= base_url('searchkiosk') ?>" method="GET" class="form-inline justify-content-center">
                                <div class="form-group mb-2 mr-2">
                                    <label for="search" class="sr-only">Search</label>
                                    <input type="text" class="form-control" id="search" name="search" placeholder="Search">
                                </div>
                                <div class="form-group mb-2 mr-2">
                                    <label for="office" class="sr-only">Office</label>
                                    <select class="form-control" id="office" name="office">
                                        <option value="">All Offices</option>
                                        <?php foreach ($offices as $office): ?>
                                            <option value="<?= $office['office_id'] ?>"><?= $office['office_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mb-2">Search</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <?php
    // Import the Generator class
    use SimpleSoftwareIO\QrCode\Generator;

    // Assuming the QR Code Generator is already autoloaded via Composer
    
    // Loop through each search result
    foreach ($searchResults as $result): ?>
        <div class="col-md-2">
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
                    <button class="btn btn-primary" onclick="printQR(<?= htmlspecialchars($qrCodeURL) ?>, '<?= esc($result['tracking_number']) ?>')">Print</button>
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