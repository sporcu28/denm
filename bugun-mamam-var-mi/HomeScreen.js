// IMPORTANT: For production, set up your Google Maps API key in app.json under expo.android.config.googleMaps.apiKey and expo.ios.config.googleMapsApiKey
// See: https://docs.expo.dev/versions/latest/sdk/map-view/#enabling-google-maps-on-ios
import React, { useEffect, useState } from 'react';
import { View, Text, StyleSheet, Modal, TextInput, Button, Platform } from 'react-native';
import MapView, { Marker } from 'react-native-maps';
import * as Location from 'expo-location';
import { db, auth } from './firebaseConfig';
import { collection, addDoc, onSnapshot, query, orderBy } from 'firebase/firestore';

export default function HomeScreen() {
  const [region, setRegion] = useState(null);
  const [markers, setMarkers] = useState([]);
  const [modalVisible, setModalVisible] = useState(false);
  const [newMarker, setNewMarker] = useState(null);
  const [note, setNote] = useState('');

  useEffect(() => {
    (async () => {
      let { status } = await Location.requestForegroundPermissionsAsync();
      if (status !== 'granted') {
        alert('Konum izni gerekli!');
        return;
      }
      let location = await Location.getCurrentPositionAsync({});
      setRegion({
        latitude: location.coords.latitude,
        longitude: location.coords.longitude,
        latitudeDelta: 0.01,
        longitudeDelta: 0.01,
      });
    })();
  }, []);

  useEffect(() => {
    const q = query(collection(db, 'mama_noktalari'), orderBy('timestamp', 'desc'));
    const unsubscribe = onSnapshot(q, (querySnapshot) => {
      const data = [];
      querySnapshot.forEach((doc) => {
        data.push({ id: doc.id, ...doc.data() });
      });
      setMarkers(data);
    });
    return unsubscribe;
  }, []);

  const handleLongPress = (e) => {
    setNewMarker(e.nativeEvent.coordinate);
    setModalVisible(true);
  };

  const handleAddMarker = async () => {
    setModalVisible(false);
    if (!newMarker) return;
    try {
      await addDoc(collection(db, 'mama_noktalari'), {
        latitude: newMarker.latitude,
        longitude: newMarker.longitude,
        note: note,
        timestamp: new Date(),
        userId: auth.currentUser ? auth.currentUser.uid : null,
      });
      setNote('');
      setNewMarker(null);
    } catch (e) {
      alert('Bir hata oluştu!');
    }
  };

  if (!region) {
    return <View style={styles.center}><Text>Konum alınıyor...</Text></View>;
  }

  return (
    <View style={{ flex: 1 }}>
      <MapView
        style={{ flex: 1 }}
        region={region}
        showsUserLocation
        onLongPress={handleLongPress}
      >
        {markers.map((marker) => (
          <Marker
            key={marker.id}
            coordinate={{ latitude: marker.latitude, longitude: marker.longitude }}
            title={marker.note || 'Mama Noktası'}
            description={marker.timestamp?.toDate?.().toLocaleString?.() || ''}
          />
        ))}
      </MapView>
      <Modal visible={modalVisible} transparent animationType="slide">
        <View style={styles.modalView}>
          <Text>Not veya emoji ekle (isteğe bağlı):</Text>
          <TextInput
            style={styles.input}
            value={note}
            onChangeText={setNote}
            placeholder="🐱🐶"
          />
          <Button title="Ekle" onPress={handleAddMarker} />
          <Button title="İptal" onPress={() => setModalVisible(false)} color="red" />
        </View>
      </Modal>
    </View>
  );
}

const styles = StyleSheet.create({
  center: { flex: 1, alignItems: 'center', justifyContent: 'center' },
  modalView: {
    margin: 40,
    backgroundColor: 'white',
    borderRadius: 10,
    padding: 20,
    alignItems: 'center',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.25,
    shadowRadius: 4,
    elevation: 5,
  },
  input: {
    borderWidth: 1,
    borderColor: '#ccc',
    borderRadius: 5,
    padding: 8,
    marginVertical: 10,
    width: 200,
  },
});