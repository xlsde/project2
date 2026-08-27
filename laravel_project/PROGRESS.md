# artirdim.com — İlerleme Takibi

Durumlar: ⬜ bekliyor · 🟡 onay bekliyor · 🔎 inceleniyor · ✅ tamamlandı

## 0. Git / Repo Temizliği
- 🟡 Emergent scaffold artıklarının silinmesi (backend/, frontend/, tests/, test_result.md, yarn.lock) — onay bekliyor
- ✅ Nested .git / submodule / gitlink taraması — temiz (yalnızca /app/.git)

## Aşamalar (küçük→büyük / risk sırası)
- ⬜ AŞAMA 1 — Genel haritalama (rapor) — SUNULDU
- ✅ AŞAMA 6B — UI: story border (border kaldırıldı + box-sizing + CSS cache-busting)
- ✅ AŞAMA 6C — UI: "Çıkış Yap" kırmızı (inline #ef4444 !important; sidebar icon korundu)
- ✅ AŞAMA 4B — Header dropdown rol bazlı görünürlük (İlanlarım=seller, Tekliflerim=buyer)
- ✅ AŞAMA 5B — Resim perf: lazy loading + Spatie/GD webp variant (card 800/thumb 400). coverUrl→webp. Backfill komutu (20/20). ~95KB→18KB (~%81). AuctionImage korundu (geriye uyumlu).
- ✅ AŞAMA 7 — UI: #1 ilk kartlar eager+fetchpriority, #2 kırık görsel placeholder fallback, #4 :focus-visible. (#3/#5/#6 zaten vardı)
- ✅ AŞAMA 8 — SEO: server-side OG/Twitter/description/canonical (ilan detayı zengin), dinamik sitemap.xml (cache) + robots.txt
- ✅ AŞAMA 5 — N+1: Browse withCount base query'ye alındı (live dahil); auctions (status,ends_at)+created_at index eklendi
- ✅ AŞAMA 6 — Kod kalitesi: sağlıklı (polling+temizlik, array serileştirme, servis katmanı). Zorunlu değişiklik yok; opsiyonel ölü dosya temizliği notlandı
- ✅ AŞAMA 4 — Güvenlik: kritik açık yok. Sıkılaştırma: presence kanalı exists kontrolü + LiveKit canPublishData yalnız yayıncı
- ✅ AŞAMA 3 — Sipariş geçişleri kilitli/idempotent (tryHoldEscrow/confirmDelivered/cancelAndRefund lockForUpdate+recheck) + orders.auction_id unique. Test edildi.
- ✅ AŞAMA 2 — Bid race condition: ZATEN güvenli (lockForUpdate + kilit içinde min yeniden hesap + post-commit broadcast). Değişiklik gerekmedi.

## Bekleyen (opsiyonel)
- buyer sayfalarına (my-bids/favorites/orders) opsiyonel `role:buyer` route kısıtı — kullanıcı kararı
- Ölü dosya temizliği (bootstrap-old.js, old-live.blade.php, yedek theme*.css)
- AuctionPolicy ölü kod (manuel abort_unless kullanılıyor)
- Görsel boyut için tam Spatie geçişi (istenirse) — şu an A-lite (webp variant) uygulandı
- ⬜ (Karar bekleyen) buyer sayfalarına opsiyonel role:buyer route kısıtı
- ⬜ (Karar bekleyen) buyer sayfalarına opsiyonel role:buyer route kısıtı
- ⬜ AŞAMA 5B — Resim yükleme performansı (analiz/öneri)
- ⬜ AŞAMA 7 — UI iyileştirme önerileri
- ⬜ AŞAMA 8 — SEO (seotools + sitemap kontrolü)
- ⬜ AŞAMA 5 — Query/performans (N+1)
- ⬜ AŞAMA 6 — Vue/Controller kod kalitesi
- ⬜ AŞAMA 4 — Güvenlik açıkları (auth/policy, LiveKit token, XSS, mass assignment, kanallar)
- ⬜ AŞAMA 3 — Bakiye/Ödeme/Sipariş bütünlüğü (DB transaction)
- ⬜ AŞAMA 2 — Teklif (bid) eşzamanlılık/race condition (en yüksek öncelik, en riskli)

> Not: Sıra "en küçük→en riskli" mantığıyla dizildi. Hangi işi önce yapacağımı kullanıcı seçecek.

## Kritik Bug Fix (2026-06) — Inertia "plain JSON response" / host uyuşmazlığı
- ✅ ÇÖZÜLDÜ. Kök neden: AppServiceProvider `URL::forceRootUrl(config('app.url'))` tüm route()/url() çıktısını APP_URL host'una (17d31f70) sabitliyordu. Farklı host'tan (vue-laravel-dev-1) girildiğinde AuctionCard show_url'leri çapraz-origin oluyor, Inertia x-inertia başlığını okuyamayıp "plain JSON response" hatası veriyordu.
- Çözüm: .env APP_URL = https://vue-laravel-dev-1.preview.emergentagent.com yapıldı (kullanıcı tek host kullanacak). ziggy.url + show_url artık doğru host. Curl ile doğrulandı.
- Not: AppServiceProvider forceRootUrl kod değişikliği YAPILMADI (kullanıcı .env yolunu seçti). İleride çok-host gerekirse istek-host bazlı forceRootUrl seçeneği mevcut.

## Kalan işlenmemiş aşamalar
- 🟡 AŞAMA 6D — inline CSS + okunabilirlik: ANALİZ SUNULDU, refactor kararı kullanıcıda (fonksiyonel kazanç yok).
- ⬜ AŞAMA 7B — responsive tutarlılık: analiz başlıyor.

## Story + Loading Screen (2026-06)
- Loading screen: sayfa (full page) yenilemede tam ekran overlay eklendi (app.blade.php #app-loader + <style>; app.js hideAppLoader mount sonrası fade-out + window load fallback). Kullanıcı isteğiyle SADECE dönen circle (logo/yazı yok), dark/light tema-duyarlı (var(--bg)/--primary/--border). SPA geçişlerinde görünmez.
- Story ring: denemeler (gap fix + solid) kullanıcı onayı almadı; kullanıcı isteğiyle TÜMÜ eski haline döndürüldü (story_ring_style segmentli conic, .story-ring img orijinal). Ek olarak `.story-item.seen .story-ring img { opacity: .9; }` kuralı SİLİNDİ (kullanıcı talebi).

## Browse redesign + iç içe kategoriler + gerçek veri (2026-06)
- Migration YOK (parent_id/tree zaten vardı). CategoryTreeSeeder: 5 kök + 17 alt kategori (Sanat→Tablo/Portre/Heykel/Baskı, Antika→Mobilya/Porselen/Saat/Kitap, Mücevherat→Yüzük/Kolye/Broş, Elektronik→Fotoğraf/Plak/Retro, Koleksiyon→Pul/Para/Model), her biri gerçek görsel (storage/catalog). RealCatalogSeeder: 28 gerçek ilan (gerçek başlık/açıklama/fiyat + indirilmiş gerçek görseller), bazıları stream_mode=live. İdempotent.
- BrowseController: explore→Category ağacı; auctions→kategori seçilince allChildrenIds ile alt kategoriler dahil + min/max fiyat + price_asc sort; live→stats + stream_mode öncelikli sıralama.
- Vue: Explore (iç içe ağaç: kök kart + alt chip'ler), Auctions (gelişmiş filtre çubuğu + aktif chip + temizle + fiyat), Live (kırmızı tema, nabız CANLI rozeti, istatistik hero, kırmızı vurgulu kartlar). Müzayedeler ile Canlı belirgin farklı.
- Stil: yeni public/assets/css/browse.css (INLINE CSS YOK), tema-duyarlı (dark/light), app.blade.php'ye link.
- Doğrulama: 3 sayfa görsel + curl (category=portre→2, min_price≥40000→9, live stats total39/streaming22). Inertia hatası yok.
- Not: eski LiveDataSeeder/AuctionSeeder ilanları hâlâ aX.jpg (404) → "Görsel bulunamadı" (bilinen P2, kullanıcı erteledi).
