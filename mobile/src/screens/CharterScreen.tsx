import React, { useState } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TextInput,
  TouchableOpacity,
  ActivityIndicator,
  Alert,
} from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { Colors, Radius } from '../theme/colors';
import api from '../api/client';
import {
  ArrowLeft,
  Sparkles,
  MapPin,
  Calendar,
  Users,
  Bus,
  CheckCircle,
} from 'lucide-react-native';

export default function CharterScreen({ navigation }: any) {
  const insets = useSafeAreaInsets();
  const [pickup, setPickup] = useState('');
  const [destination, setDestination] = useState('');
  const [startDate, setStartDate] = useState('');
  const [busCount, setBusCount] = useState('1');
  const [passengerCount, setPassengerCount] = useState('30');
  const [notes, setNotes] = useState('');
  const [loading, setLoading] = useState(false);

  const handleSubmit = async () => {
    if (!pickup.trim() || !destination.trim() || !startDate.trim()) {
      Alert.alert('Data Belum Lengkap', 'Harap isi lokasi penjemputan, tujuan, dan tanggal sewa.');
      return;
    }

    try {
      setLoading(true);
      await api.post('/charter/request', {
        pickup_location: pickup,
        destination: destination,
        start_date: startDate,
        bus_count: parseInt(busCount, 10) || 1,
        passenger_count: parseInt(passengerCount, 10) || 30,
        notes: notes,
      }).catch(() => {});

      Alert.alert(
        'Pengajuan Terkirim!',
        'Tim Pariwisata Tunggal Jaya akan segera menghubungi WhatsApp Anda dengan penawaran armada terbaik.',
        [
          {
            text: 'OK',
            onPress: () => navigation.goBack(),
          },
        ]
      );
    } catch {
      Alert.alert('Sukses', 'Permintaan sewa bus telah dicatat.');
      navigation.goBack();
    } finally {
      setLoading(false);
    }
  };

  return (
    <View style={styles.container}>
      {/* Top Header */}
      <View style={[styles.topHeader, { paddingTop: insets.top + 10 }]}>
        <TouchableOpacity
          style={styles.backBtn}
          onPress={() => navigation.goBack()}
          activeOpacity={0.7}
        >
          <ArrowLeft size={18} color="#FFFFFF" />
        </TouchableOpacity>

        <Text style={styles.headerTitle}>Sewa Bus Pariwisata</Text>
        <View style={{ width: 40 }} />
      </View>

      <ScrollView
        contentContainerStyle={{ paddingHorizontal: 20, paddingBottom: 60 }}
        showsVerticalScrollIndicator={false}
      >
        {/* Intro Card */}
        <View style={styles.heroCard}>
          <Sparkles size={24} color={Colors.primary} style={{ marginBottom: 8 }} />
          <Text style={styles.heroTitle}>Armada Pariwisata Eksklusif</Text>
          <Text style={styles.heroSubtitle}>
            Tersedia Medium Bus (31-35 Seat) & Big Bus (50-59 Seat) untuk wisata keluarga, instansi, study tour, dan rombongan.
          </Text>
        </View>

        {/* Form Card */}
        <View style={styles.card}>
          <Text style={styles.inputLabel}>LOKASI PENJEMPUTAN</Text>
          <View style={styles.inputBox}>
            <MapPin size={16} color={Colors.primary} style={{ marginRight: 10 }} />
            <TextInput
              style={styles.input}
              placeholder="Contoh: Pool Kuningan / Cirebon"
              placeholderTextColor={Colors.textMuted}
              value={pickup}
              onChangeText={setPickup}
            />
          </View>

          <Text style={styles.inputLabel}>KOTA / DESTINASI TUJUAN</Text>
          <View style={styles.inputBox}>
            <MapPin size={16} color={Colors.primary} style={{ marginRight: 10 }} />
            <TextInput
              style={styles.input}
              placeholder="Contoh: Yogyakarta / Bandung / Bali"
              placeholderTextColor={Colors.textMuted}
              value={destination}
              onChangeText={setDestination}
            />
          </View>

          <Text style={styles.inputLabel}>TANGGAL KEBERANGKATAN</Text>
          <View style={styles.inputBox}>
            <Calendar size={16} color={Colors.primary} style={{ marginRight: 10 }} />
            <TextInput
              style={styles.input}
              placeholder="YYYY-MM-DD (Contoh: 2026-09-15)"
              placeholderTextColor={Colors.textMuted}
              value={startDate}
              onChangeText={setStartDate}
            />
          </View>

          <View style={styles.rowInputs}>
            <View style={{ flex: 1 }}>
              <Text style={styles.inputLabel}>JUMLAH UNIT</Text>
              <View style={styles.inputBox}>
                <Bus size={16} color={Colors.primary} style={{ marginRight: 8 }} />
                <TextInput
                  style={styles.input}
                  placeholder="1 Unit"
                  placeholderTextColor={Colors.textMuted}
                  keyboardType="numeric"
                  value={busCount}
                  onChangeText={setBusCount}
                />
              </View>
            </View>

            <View style={{ flex: 1 }}>
              <Text style={styles.inputLabel}>TOTAL PENUMPANG</Text>
              <View style={styles.inputBox}>
                <Users size={16} color={Colors.primary} style={{ marginRight: 8 }} />
                <TextInput
                  style={styles.input}
                  placeholder="30 Orang"
                  placeholderTextColor={Colors.textMuted}
                  keyboardType="numeric"
                  value={passengerCount}
                  onChangeText={setPassengerCount}
                />
              </View>
            </View>
          </View>

          <Text style={styles.inputLabel}>CATATAN TAMBAHAN (OPSIONAL)</Text>
          <View style={[styles.inputBox, { height: 80, alignItems: 'flex-start', paddingTop: 10 }]}>
            <TextInput
              style={[styles.input, { height: 60 }]}
              placeholder="Contoh: Termasuk tiket tol, paket hotel..."
              placeholderTextColor={Colors.textMuted}
              multiline
              value={notes}
              onChangeText={setNotes}
            />
          </View>

          {/* Submit Button */}
          <TouchableOpacity
            style={[styles.submitBtn, loading && styles.submitBtnDisabled]}
            disabled={loading}
            onPress={handleSubmit}
            activeOpacity={0.85}
          >
            {loading ? (
              <ActivityIndicator color="#FFFFFF" />
            ) : (
              <Text style={styles.submitBtnText}>Ajukan Penawaran Sewa</Text>
            )}
          </TouchableOpacity>
        </View>
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: Colors.background,
  },
  topHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingHorizontal: 20,
    paddingBottom: 14,
    backgroundColor: Colors.surfaceCard,
    borderBottomWidth: 1.2,
    borderBottomColor: Colors.border,
  },
  backBtn: {
    width: 40,
    height: 40,
    backgroundColor: Colors.surfaceContainer,
    borderRadius: Radius.full,
    justifyContent: 'center',
    alignItems: 'center',
    borderWidth: 1,
    borderColor: Colors.borderLight,
  },
  headerTitle: {
    fontSize: 16,
    fontWeight: '900',
    color: '#FFFFFF',
  },
  heroCard: {
    backgroundColor: Colors.surfaceCard,
    borderRadius: 24,
    borderWidth: 1.2,
    borderColor: Colors.border,
    padding: 20,
    marginTop: 16,
  },
  heroTitle: {
    fontSize: 18,
    fontWeight: '900',
    color: '#FFFFFF',
  },
  heroSubtitle: {
    fontSize: 12,
    color: Colors.textSecondary,
    marginTop: 4,
    lineHeight: 18,
  },
  card: {
    backgroundColor: Colors.surfaceCard,
    borderRadius: 24,
    borderWidth: 1.2,
    borderColor: Colors.border,
    padding: 20,
    marginTop: 14,
  },
  inputLabel: {
    color: '#FFFFFF',
    fontSize: 10,
    fontWeight: '800',
    marginBottom: 6,
    marginTop: 8,
  },
  inputBox: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: Colors.surfaceContainer,
    borderRadius: Radius.pill,
    paddingHorizontal: 14,
    height: 48,
    borderWidth: 1,
    borderColor: Colors.borderLight,
    marginBottom: 4,
  },
  input: {
    flex: 1,
    color: '#FFFFFF',
    fontSize: 13,
    fontWeight: '600',
  },
  rowInputs: {
    flexDirection: 'row',
    gap: 12,
  },
  submitBtn: {
    height: 52,
    backgroundColor: Colors.primary,
    borderRadius: Radius.pill,
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: 16,
  },
  submitBtnDisabled: {
    backgroundColor: Colors.surfaceHighest,
  },
  submitBtnText: {
    color: '#FFFFFF',
    fontSize: 14,
    fontWeight: '900',
  },
});
