
<div class="hero">
    <div class="hero-slide">
        <div
          class="img overlay"
          style="background-image: url('assets2/images/Mindoro.png')"
        ></div>
        <div
          class="img overlay"
          style="background-image: url('assets2/images/hero_bg_2.jpg')"
        ></div>
        <div
          class="img overlay"
          style="background-image: url('assets2/images/hero_bg_1.jpg')"
        ></div>
      </div>

<div class="container">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Document Transaction</h4>
                            <p class="card-description">Track and update documents.</p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Tracking Number</th>
                                    <th>Recipient</th>
                                    <th>Status</th>
                                    <th>Added on</th>
                                    <th>Comment</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <?php foreach ($documents as $document): ?>
                                <tr>
                                    <td><?= isset($document['title']) ? $document['title'] : '' ?></td>
                                    <td><?= isset($document['tracking_number']) ? $document['tracking_number'] : '' ?></td>
                                    <td><?= isset($document['office_name']) ? $document['office_name'] : '' ?></td>
                                    <td><?= isset($document['status']) ? $document['status'] : '' ?></td>
                                    <td><?= isset($document['date_of_document']) ? date('F d, Y', strtotime($document['date_of_document'])) : '' ?></td>
                                    <td><?= isset($document['action']) ? $document['action'] : '' ?></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-info">
                                            <i class="mdi mdi-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
