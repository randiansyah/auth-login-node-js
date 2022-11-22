<div style="font-size:14px;color:rgba(49,53,59,0.96);background-color:#ffffff;font-family:open sans,tahoma,sans-serif">
	<div style="overflow:hidden;display:block;width:90%;max-width:600px;margin:50px auto;line-height:1.5;border:1px solid #e5e5e5;border-radius:3px">
		<div style="display:block;padding:24px 32px 0">

			<a href="https://blanjaque.com/" target="_blank" style="display: block;text-align: center;">
				<img src="https://blanjaque.com/cloud/img/img/logo.png" style="display:inline-block;height:32px;width:145px;outline:none;margin-bottom:32px!important">
			</a>

			<div style="margin-bottom:32px!important">
				<div style="margin-bottom:32px!important">
					<p style="margin-top:0!important">
						Halo <strong>Que!</strong>
					</p>
					<p>
						Masukkan kode verifikasi berikut ini untuk mengaktifkan akun anda.
					</p>
					<h3 style="font-size:1.2rem;text-align: center;margin:0!important">KODE AKTIVASI</h3>
					<div style="display:block;padding:7px 0;font-size:20px;font-weight:800;line-height:1.29;text-align:center;color:rgba(49,53,59,0.96);background-color:#e5e5e552;border-radius:8px;text-decoration:none;margin:0 auto;max-width:272px;border:1px solid #e5e7e9;width:200px">
						<?php echo $code; ?>
					</div>
				</div>
				<div style="margin-bottom:32px!important">
					<p style="text-align: center;"> 
						Atau, Kamu juga bisa mengeklik tombol di bawah ini.
					</p>
					<a href="<?php echo 'https://blanjaque.com/register?type=email&email='.$hash.'=&otpcode='.$code?>" style="display:block;padding:13px 0;font-size:16px;font-weight:600;line-height:1.38;text-align:center;color:#ffffff;background-color:#ed1c24;border-radius:8px;text-decoration:none;margin:0 auto;max-width:272px" target="_blank">Aktifkan Sekarang</a>
				</div>
				<div style="font-size:0.86rem;margin-bottom:16px!important">
					<h4 style="margin:0!important">Catatan:</h4>
					<p style="margin-top:0!important">
						Kode di atas hanya berlaku 30 menit. Harap untuk tidak menyebarkan kode ini.
					</p>
					<p>
						Email ini dibuat secara otomatis, mohon tidak membalas. Jika butuh bantuan, silakan <a href="<?php echo base_url('help')?>" style="text-decoration:none;color:#ed1c24;white-space:nowrap;font-weight:bold" target="_blank">hubungi kami</a>
					</p>
				</div>
			</div>
			<div style="overflow:hidden;display:block;padding:16px 32px;text-align:center;font-size:12px;color:rgba(49,53,59,0.68);border-top:1px solid #e5e5e5;line-height:1.5">
				<p style="margin:0">
					© <?php echo date('Y')?>, PT <span class="il">BlanjaQue</span>
				</p>
			</div>
		</div>
	</div>
</div>