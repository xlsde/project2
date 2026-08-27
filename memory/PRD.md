# artirdim — Laravel 12 + Inertia + Vue3 (Canlı Açık Artırma Pazaryeri)

## Problem Statement
Kullanıcının GitHub'daki (github.com/xlsde/project) Laravel 12 + Inertia + Vue projesi.
İstek 1: Projeyi Emergent ortamında çalışır hale getir; iç içe git/submodule sorununu gider,
gereksiz Emergent iskele klasörlerini sil (sadece laravel_project kalsın).
İstek 2 (toast birleştirme):
 - Tüm HTML "başarı" mesajları (profil güncellendi, kullanıcı eklendi/silindi vb.) yerine
   profil-kopyalama toast'ıyla (sağ-alt .pf-toast) AYNI stilde toast gösterilsin. Gereksiz success HTML silinsin.
 - Profil güncellenince sayfa refresh olmasın; orada da toast çıksın.
 - Bug: her sayfa açılışında / her işlemde "Mesajınız iletildi..." toast'ı çıkıyor -> düzeltilecek.
 - Bug: eski toast kalıyor; kategori ekle -> "eklendi", sonra sil -> hem "eklendi" hem "silindi" -> düzeltilecek.

## Architecture / Setup
- PHP 8.2, MariaDB (auction/auction/auction123), Redis. Supervisor `laravel` -> php artisan serve :3000.
- Vite build (public/build). QUEUE=sync, MAIL/BROADCAST=log, SCOUT=database.
- Test users: admin/seller/buyer @test.com / password.

## Done (2026-06)
- Repo kuruldu, çalışır durumda (preview :3000). İç içe .git kaldırıldı, klasör düzleştirildi, iskele silindi.
- Birleşik toast: public/assets/js/custom/app-toast.js (window.appToast/ajaxToast) + ajax-delete.js aynı stile bağlandı.
- Flash->toast köprüsü resources/js/app.js (router before/success; partial reload'da toast yok -> duplicate/stale fix).
- HandleInertiaRequests: email_success/password_success flash paylaşımı eklendi.
- Tüm sayfalardaki inline flash success/error banner'ları kaldırıldı (Contact, Categories, Seller/Buyer orders,
  Sales, Balance, Support, Settings, Seller Profile, Auctions, Profile/Show).
- Profile/Show.vue formları (update/email/password/privacy) Inertia router.post ile (preserveScroll+preserveState) -> refresh yok.

## Backlog / Next
- P1: Reverb/LiveKit canlı yayın gerçek servisleri (şu an log/boş anahtar).
- P2: Meilisearch (şu an SCOUT=database).
- P2: Kalıcılık için pod restart sonrası otomatik kurulum (serve.sh DB'yi başlatıyor; composer/npm kalıcı).
