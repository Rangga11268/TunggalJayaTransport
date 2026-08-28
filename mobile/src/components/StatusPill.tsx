import React from 'react';
import { View, Text, StyleSheet } from 'react-native';
import { CheckCircle2, Clock, XCircle, AlertCircle } from 'lucide-react-native';

interface StatusPillProps {
  status: string;
  type?: 'success' | 'warning' | 'danger' | 'info' | 'neutral';
}

export const StatusPill: React.FC<StatusPillProps> = ({ status, type }) => {
  const norm = (status || '').toLowerCase();

  let derivedType = type;
  if (!derivedType) {
    if (norm.includes('lunas') || norm.includes('paid') || norm.includes('terkonfirmasi') || norm.includes('confirmed') || norm.includes('aktif') || norm.includes('active')) {
      derivedType = 'success';
    } else if (norm.includes('menunggu') || norm.includes('pending') || norm.includes('partial')) {
      derivedType = 'warning';
    } else if (norm.includes('batal') || norm.includes('cancel') || norm.includes('gagal') || norm.includes('failed')) {
      derivedType = 'danger';
    } else if (norm.includes('berangkat') || norm.includes('departed')) {
      derivedType = 'neutral';
    } else {
      derivedType = 'info';
    }
  }

  const getStyle = () => {
    switch (derivedType) {
      case 'success':
        return { bg: '#ECFDF5', text: '#059669', border: '#A7F3D0', icon: CheckCircle2 };
      case 'warning':
        return { bg: '#FFFBEB', text: '#D97706', border: '#FDE68A', icon: Clock };
      case 'danger':
        return { bg: '#FEF2F2', text: '#DC2626', border: '#FECACA', icon: XCircle };
      case 'neutral':
        return { bg: '#F3F4F6', text: '#6B7280', border: '#E5E7EB', icon: Clock };
      case 'info':
      default:
        return { bg: '#EFF6FF', text: '#2563EB', border: '#BFDBFE', icon: AlertCircle };
    }
  };

  const current = getStyle();
  const IconComp = current.icon;

  return (
    <View style={[styles.pill, { backgroundColor: current.bg, borderColor: current.border }]}>
      <IconComp size={11} color={current.text} style={{ marginRight: 4 }} />
      <Text style={[styles.text, { color: current.text }]}>
        {status.toUpperCase()}
      </Text>
    </View>
  );
};

const styles = StyleSheet.create({
  pill: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: 8,
    borderWidth: 1,
  },
  text: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 10.5,
    letterSpacing: 0.3,
  },
});

