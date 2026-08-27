import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  ActivityIndicator,
  Alert,
} from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { Colors, Radius } from '../theme/colors';
import api from '../api/client';
import {
  ArrowLeft,
  Tag,
  Copy,
  CheckCircle,
  Percent,
} from 'lucide-react-native';

export default function PromoScreen({ navigation }: any) {
  const insets = useSafeAreaInsets();
  const [promos, setPromos] = useState<any[]>([
    {
      code: 'TJHEMAT20',
      title: 'Diskon Spesial Tiket AKAP',
      discount: 'Rp 20.000',
      min_spend: 'Rp 100.000',
      valid_until: '31 Des 2026',
    },
    {
      code: 'TJPARIWISATA',
      title: 'Cashback Sewa Bus Rombongan',
      discount: 'Rp 250.000',
      min_spend: 'Rp 3.000.000',
      valid_until: '31 Des 2026',
    },
    {
      code: 'MEMBERVIP',
      title: 'Potongan Khusus Member Setia',
      discount: '15% OFF',
      min_spend: 'Semua Rute',
      valid_until: 'Selalu Aktif',
    },
  ]);
  const [copiedCode, setCopiedCode] = useState<string | null>(null);

  const copyToClipboard = (code: string) => {
    setCopiedCode(code);
    Alert.alert('Kupon Disalin!', `Kode promo ${code} siap digunakan saat checkout.`);
    setTimeout(() => setCopiedCode(null), 3000);
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

        <Text style={styles.headerTitle}>Pusat Kupon & Promo</Text>
        <View style={{ width: 40 }} />
      </View>

      <ScrollView
        contentContainerStyle={{ paddingHorizontal: 20, paddingBottom: 60 }}
        showsVerticalScrollIndicator={false}
      >
        {promos.map((item, idx) => (
          <View key={idx} style={styles.promoCard}>
            <View style={styles.cardHeader}>
              <View style={styles.iconCircle}>
                <Percent size={18} color={Colors.primary} />
              </View>
              <View style={{ flex: 1 }}>
                <Text style={styles.promoTitle}>{item.title}</Text>
                <Text style={styles.promoDiscount}>Hemat {item.discount}</Text>
              </View>
            </View>

            <View style={styles.divider} />

            <View style={styles.cardFooter}>
              <View>
                <Text style={styles.validText}>Min. Transaksi: {item.min_spend}</Text>
                <Text style={styles.validText}>Berlaku s/d: {item.valid_until}</Text>
              </View>

              <TouchableOpacity
                style={styles.copyBtn}
                onPress={() => copyToClipboard(item.code)}
                activeOpacity={0.8}
              >
                <Text style={styles.codeText}>{item.code}</Text>
                <Copy size={13} color={Colors.primary} style={{ marginLeft: 6 }} />
              </TouchableOpacity>
            </View>
          </View>
        ))}
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
  promoCard: {
    backgroundColor: Colors.surfaceCard,
    borderRadius: 24,
    borderWidth: 1.2,
    borderColor: Colors.border,
    padding: 18,
    marginTop: 14,
  },
  cardHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 14,
  },
  iconCircle: {
    width: 44,
    height: 44,
    borderRadius: Radius.full,
    backgroundColor: Colors.primaryContainer,
    justifyContent: 'center',
    alignItems: 'center',
  },
  promoTitle: {
    color: '#FFFFFF',
    fontSize: 14,
    fontWeight: '800',
  },
  promoDiscount: {
    color: Colors.primary,
    fontSize: 16,
    fontWeight: '900',
    marginTop: 2,
  },
  divider: {
    height: 1,
    backgroundColor: Colors.border,
    marginVertical: 14,
  },
  cardFooter: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  validText: {
    color: Colors.textSecondary,
    fontSize: 11,
  },
  copyBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: Colors.surfaceContainer,
    paddingHorizontal: 12,
    paddingVertical: 7,
    borderRadius: Radius.pill,
    borderWidth: 1,
    borderColor: Colors.borderLight,
  },
  codeText: {
    color: '#FFFFFF',
    fontSize: 12,
    fontWeight: '900',
    letterSpacing: 0.5,
  },
});
