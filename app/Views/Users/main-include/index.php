<div class="hero">
      <div class="hero-slide">
      </div>

      <div class="container">
        <div class="row justify-content-center align-items-center">
          <div class="col-lg-9 text-center">
            <h1 class="heading" data-aos="fade-up">
              Effortlessly Track Your Documents Online!
            </h1>
            <p style="color: white;">Simply Enter Your Document Tracking Number Below:</p>
            <form action="<?= route_to('searchResults') ?>" method="POST" class="narrow-w form-search d-flex align-items-stretch mb-3" data-aos="fade-up" data-aos-delay="200">
            <?= csrf_field() ?>   
            <input
                name="tracking_number" placeholder="Enter Document Tracking Number..."
                class="form-control px-4"/>
                <button type="submit" class="btn btn-primary" style="background-color: #9a2db9;">Search</button>
            </form>
          </div>
        </div>
      </div>
    </div>