# Bugün Mamam Var mı?

Mobil uygulama: Sokak hayvanları için mama bırakılan noktaları harita üzerinde işaretleyin ve paylaşın.

## Özellikler
- Firebase ile kullanıcı kimlik doğrulama (anonim giriş ile otomatik)
- Google Haritalar üzerinde mama noktalarını görme ve ekleme
- Mama noktası eklerken not/emoji ve otomatik zaman damgası
- Firestore'da tüm mama noktalarını saklama
- Admin paneli ile tüm noktaları listeleme ve silme
- Günlük mama bırakma hatırlatıcı bildirimi (isteğe bağlı, Expo Notifications ile eklenebilir)

## Kurulum

1. **Projeyi klonlayın veya indirin:**
   ```bash
   cd bugun-mamam-var-mi
   npm install
   ```

2. **Firebase kurulumu:**
   - [Firebase Console](https://console.firebase.google.com/) üzerinden yeni bir proje oluşturun.
   - Firestore Database'i etkinleştirin.
   - Authentication > Sign-in method > Anonymous'ı etkinleştirin.
   - Proje ayarlarından web uygulaması ekleyin ve `firebaseConfig.js` dosyasındaki alanları doldurun:
     ```js
     const firebaseConfig = {
       apiKey: 'YOUR_API_KEY',
       authDomain: 'YOUR_AUTH_DOMAIN',
       projectId: 'YOUR_PROJECT_ID',
       storageBucket: 'YOUR_STORAGE_BUCKET',
       messagingSenderId: 'YOUR_MESSAGING_SENDER_ID',
       appId: 'YOUR_APP_ID',
     };
     ```

3. **Google Maps API Key:**
   - [Google Cloud Console](https://console.cloud.google.com/) üzerinden bir Maps API anahtarı alın.
   - `app.json` dosyasına ekleyin:
     ```json
     "expo": {
       ...
       "android": {
         "config": {
           "googleMaps": {
             "apiKey": "YOUR_GOOGLE_MAPS_API_KEY"
           }
         }
       },
       "ios": {
         "config": {
           "googleMapsApiKey": "YOUR_GOOGLE_MAPS_API_KEY"
         }
       }
     }
     ```

4. **Projeyi başlatın:**
   ```bash
   npx expo start
   ```
   QR kodu Expo Go ile okutun veya Android/iOS emülatöründe açın.

## Kullanım
- **Harita:** Uzun basarak mama noktası ekleyin, not/emoji bırakın.
- **Admin Panel:** Tüm noktaları listeleyin ve silin.

## Admin Paneli
- Uygulama içindeki "Admin Panel" sekmesinden erişilir.
- Tüm mama noktalarını görebilir ve silebilirsiniz.

## Bildirimler (Opsiyonel)
- Günlük mama bırakma hatırlatıcısı eklemek için `expo-notifications` paketini kullanabilirsiniz.

## Notlar
- Uygulama MVP olarak sade ve hızlı çalışacak şekilde tasarlanmıştır.
- Geliştirme için Expo kullanılmıştır.

---

Herhangi bir sorunda bana ulaşabilirsiniz!