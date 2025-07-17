import React, { useEffect, useState } from 'react';
import { View, Text, FlatList, Button, StyleSheet, Alert } from 'react-native';
import { supabase } from './supabaseClient';

export default function AdminScreen() {
  const [locations, setLocations] = useState([]);

  useEffect(() => {
    fetchLocations();
    const subscription = supabase
      .channel('public:mama_noktalari')
      .on('postgres_changes', { event: '*', schema: 'public', table: 'mama_noktalari' }, fetchLocations)
      .subscribe();
    return () => {
      supabase.removeChannel(subscription);
    };
  }, []);

  const fetchLocations = async () => {
    const { data, error } = await supabase
      .from('mama_noktalari')
      .select('*')
      .order('timestamp', { ascending: false });
    if (!error) setLocations(data || []);
  };

  const handleDelete = (id) => {
    Alert.alert('Sil', 'Bu mama noktasını silmek istediğinize emin misiniz?', [
      { text: 'İptal', style: 'cancel' },
      { text: 'Sil', style: 'destructive', onPress: async () => {
        await supabase.from('mama_noktalari').delete().eq('id', id);
        fetchLocations();
      }},
    ]);
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Tüm Mama Noktaları</Text>
      <FlatList
        data={locations}
        keyExtractor={(item) => item.id?.toString()}
        renderItem={({ item }) => (
          <View style={styles.item}>
            <Text>Koordinat: {item.latitude?.toFixed(5)}, {item.longitude?.toFixed(5)}</Text>
            <Text>Not: {item.note || '-'}</Text>
            <Text>Tarih: {item.timestamp ? new Date(item.timestamp).toLocaleString() : ''}</Text>
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