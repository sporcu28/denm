import React, { useEffect, useState } from 'react';
import { View, Text, FlatList, Button, StyleSheet, Alert } from 'react-native';
import { db } from './firebaseConfig';
import { collection, onSnapshot, query, orderBy, deleteDoc, doc } from 'firebase/firestore';

export default function AdminScreen() {
  const [locations, setLocations] = useState([]);

  useEffect(() => {
    const q = query(collection(db, 'mama_noktalari'), orderBy('timestamp', 'desc'));
    const unsubscribe = onSnapshot(q, (querySnapshot) => {
      const data = [];
      querySnapshot.forEach((doc) => {
        data.push({ id: doc.id, ...doc.data() });
      });
      setLocations(data);
    });
    return unsubscribe;
  }, []);

  const handleDelete = (id) => {
    Alert.alert('Sil', 'Bu mama noktasını silmek istediğinize emin misiniz?', [
      { text: 'İptal', style: 'cancel' },
      { text: 'Sil', style: 'destructive', onPress: async () => {
        await deleteDoc(doc(db, 'mama_noktalari', id));
      }},
    ]);
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Tüm Mama Noktaları</Text>
      <FlatList
        data={locations}
        keyExtractor={(item) => item.id}
        renderItem={({ item }) => (
          <View style={styles.item}>
            <Text>Koordinat: {item.latitude.toFixed(5)}, {item.longitude.toFixed(5)}</Text>
            <Text>Not: {item.note || '-'}</Text>
            <Text>Tarih: {item.timestamp?.toDate?.().toLocaleString?.() || ''}</Text>
            <Button title="Sil" color="red" onPress={() => handleDelete(item.id)} />
          </View>
        )}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 16, backgroundColor: '#fff' },
  title: { fontSize: 20, fontWeight: 'bold', marginBottom: 16 },
  item: { marginBottom: 16, padding: 12, backgroundColor: '#f2f2f2', borderRadius: 8 },
});