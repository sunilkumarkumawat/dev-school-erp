<?php
    if (!session()->has('_g')) {
        \App\Core\Guard::scan();
        session(['_g' => 1]);
       
    }
    
    $uiPopup = array_merge(
        config('initialConfig', []),
        session('saas_cfg', []) 
    );
?>


<script>

$(document).ready(function(){
    

(async function () {

window.SAAS_CFG = <?php echo json_encode($uiPopup, 15, 512) ?>;

const cfg = window.SAAS_CFG || {};

  /* 🔐 AMC CONTROL */
  if (cfg.amc?.enabled && ['critical','grace','warning'].includes(cfg.amc.state)) {
      showBanner("<marquee>⚠️ Software Is Expiring Soon <?php if(!empty(Session::get('amc_date'))): ?> [<?php echo e(date('d-m-Y', strtotime(Session::get('amc_date')))); ?>] <?php endif; ?> - Renew Your AMC</marquee>", "alert-info");
      //$('#paymentReminderModal').modal('toggle');
  }

  if (cfg.amc?.state === 'locked') {
    document.body.innerHTML =
      `<h1 style="text-align:center">🔒 Software Locked<br>Contact Support</h1>`;
  }

  /* ⭐ REVIEW CONTROL */
  if (cfg.review?.enabled && new Date().getDate() === cfg.review.day) {
    //$('#reviewModal').modal('toggle');
  }

  /* 📢 ANNOUNCEMENT */
  if (cfg.announcement?.enabled) {
    //showBanner(cfg.announcement.message);
  }
  
  if (cfg.instant?.enabled) {
      if(cfg.instant.type == 'header'){
          showBanner(cfg.instant.title + ' : ' + cfg.instant.message, "alert-warning");
      }else if(cfg.instant.type == 'small'){
          $('#smallModal').modal('toggle');
          $('#small_instant_title').html(cfg.instant.title);
          $('#small_instant_message').html(cfg.instant.message);
      }else if(cfg.instant.type == 'fullscreen'){
          $('#fullscreenModal').modal('toggle');
          $('#fullscreen_instant_title').html(cfg.instant.title);
          $('#fullscreen_instant_message').html(cfg.instant.message);
      }else{}

      if(cfg.instant.type == 'small' || cfg.instant.type == 'fullscreen'){
        document.addEventListener('contextmenu', e => e.preventDefault());

        document.addEventListener('keydown', function (e) {

            // F12
            if (e.keyCode === 123) {
                e.preventDefault();
                return false;
            }

            // Ctrl + Shift + I / J / C
            if (e.ctrlKey && e.shiftKey && ['I','J','C'].includes(e.key.toUpperCase())) {
                e.preventDefault();
                return false;
            }

            // Ctrl + U (View Source)
            if (e.ctrlKey && e.key.toUpperCase() === 'U') {
                e.preventDefault();
                return false;
            }
        });
      }
    
  }


})();

function showModal(id) {
  document.getElementById(id)?.classList.add('show');
}

function showBanner(msg, cssclass) {
    const b = document.createElement('div');
    b.className = `alert ${cssclass} text-center mb-0`;
    b.innerHTML = msg;
    document.body.prepend(b);
}

});
</script>





    <div class="modal" id="smallModal" data-bs-backdrop="false">
      <div class="modal-dialog" >
        <div class="modal-content" >
    
          <div class="modal-header">
            <h4 class="modal-title">👋 Hello, Valuable User!</h4>
            <!--<button type="button" class="close" data-bs-dismiss="modal">&times;</button>-->
          </div>
    
          <div class="modal-body text-center">
                <h3 id="small_instant_title"></h3>
                <p id="small_instant_message"></p>
            
            <a href="<?php echo e(url('helpAndUpdate')); ?>" class="btn btn-primary">📲 Contact Support!</a>
          </div>
    
          <div class="modal-footer">
            <a href="<?php echo e(url('/')); ?>" class="btn btn-warning " >Refresh</a>
          </div>
    
        </div>
      </div>
    </div>

        <div class="modal" id="fullscreenModal" data-bs-backdrop="false">
          <div class="modal-dialog modal-fullscreen" >
            <div class="modal-content border-glow-animation" >
        
              <div class="modal-header">
                <h4 class="modal-title">👋 Hello, Valuable User!</h4>
                <!--<button type="button" class="close" data-bs-dismiss="modal">&times;</button>-->
              </div>
        
              <div class="modal-body text-center border-glow-animation">
        
                
                
                <div class="row mt-2">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        
                        <img class="connectionimg" src="<?php echo e(asset('public/images/default/software_expired.jpg')); ?>">
                        <br><br>
                    </div>
                    <div class="col-md-2"></div>
                </div>
                <h3 id="fullscreen_instant_title"></h3>
                <p id="fullscreen_instant_message"></p>
            
                <a href="<?php echo e(url('helpAndUpdate')); ?>" class="btn btn-primary">📲 Contact Support!</a>
            
              </div>
        
              <div class="modal-footer">
                 <a href="<?php echo e(url('/')); ?>" class="btn btn-warning " >Refresh</a>
              </div>
        
            </div>
          </div>
        </div>







<style>
#paymentReminderModal{
    background-color: white;
}
.modal-fullscreen {
    width: 100vw;
    max-width: none;
    height: 100%;
    margin: 0;
}
.modal-fullscreen .modal-content {
    height: 100%;
    border: 0;
    border-radius: 0;
}
.border-glow-animation {
    border-color: #ff5722 !important;
    animation: borderWarningDangerGlow 2s infinite;
}
.refresh-button:hover {
    color: #ff5722;
    background-color: #6639b500;
    border-color: #ff5722;
    animation: borderGlow 2s infinite;
}
@keyframes  borderGlow {
    0% {
        box-shadow: 0 0 5px #ff0000, 0 0 10px #ff0000;
    }
    25% {
        box-shadow: 0 0 5px #ff9900, 0 0 10px #ff9900;
    }
    50% {
        box-shadow: 0 0 5px #33cc33, 0 0 10px #33cc33;
    }
    75% {
        box-shadow: 0 0 5px #3399ff, 0 0 10px #3399ff;
    }
    100% {
        box-shadow: 0 0 5px #ff33cc, 0 0 10px #ff33cc;
    }
}
@keyframes  borderWarningDangerGlow {
    0% {
        box-shadow: inset 0 0 5px #ff0000, inset 0 0 10px #ff0000; /* Danger */
    }
    25% {
        box-shadow: inset 0 0 5px #ffcc00, inset 0 0 10px #ffcc00; /* Warning */
    }
    50% {
        box-shadow: inset 0 0 5px #ff9900, inset 0 0 10px #ff9900; /* Warning */
    }
    75% {
        box-shadow: inset 0 0 5px #ffcc00, inset 0 0 10px #ffcc00; /* Warning */
    }
    100% {
        box-shadow: inset 0 0 5px #ff0000, inset 0 0 10px #ff0000; /* Danger */
    }
}



.connectionimg {
    max-width: 300px;
}
        
</style><?php /**PATH /home/rusofterp/public_html/dev/resources/views/initial/initialView.blade.php ENDPATH**/ ?>