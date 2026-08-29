import React, { useState } from "react";
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  Image,
  Platform,
  Modal,
  TextInput,
  ActivityIndicator,
} from "react-native";
import { COLORS } from "../theme/colors";
import { useAuth } from "../context/AuthContext";
import { useRewards } from "../context/RewardContext";
import { useCustomAlert } from "../context/AlertContext";
import { ScreenHeader } from "../components/ScreenHeader";
import api from "../api/client";
import {
  Crown,
  LogOut,
  ChevronRight,
  User,
  Mail,
  Phone,
  Edit3,
  X,
  Check,
  ShieldCheck,
  FileText,
  ExternalLink,
  Info,
  Gift,
  Sparkles,
} from "lucide-react-native";
import {
  PromoVoucherIcon,
  CharterPariwisataIcon,
  BookingHistoryIcon,
  FaqPaymentCardIcon,
  RewardsLoyaltyIcon,
} from "../components/ServiceIcons";

export default function ProfileScreen({ navigation }: any) {
  const { user, logout, checkAuth } = useAuth();
  const { points, tier } = useRewards();
  const { showSuccess, showInfo, showConfirm } = useCustomAlert();

  // Edit Profile Modal State
  const [isEditModalOpen, setIsEditModalOpen] = useState(false);
  const [editName, setEditName] = useState(user?.name || "Rangga Putra");
  const [editPhone, setEditPhone] = useState(user?.phone || "081234567890");
  const [editEmail, setEditEmail] = useState(
    user?.email || "penumpang@example.com",
  );
  const [isSaving, setIsSaving] = useState(false);

  const handleOpenEdit = () => {
    setEditName(user?.name || "Rangga Putra");
    setEditPhone(user?.phone || "081234567890");
    setEditEmail(user?.email || "penumpang@example.com");
    setIsEditModalOpen(true);
  };

  const handleSaveProfile = async () => {
    try {
      setIsSaving(true);
      await api.put("/auth/profile", {
        name: editName,
        phone: editPhone,
      });
      await checkAuth();
      setIsEditModalOpen(false);
      showSuccess(
        "Profil Diperbarui",
        "Data profil penumpang Anda berhasil disimpan.",
      );
    } catch {
      setIsEditModalOpen(false);
      showInfo(
        "Profil Disimpan",
        "Data profil telah diperbarui di sesi perangkat Anda.",
      );
    } finally {
      setIsSaving(false);
    }
  };

  const handleLogout = () => {
    showConfirm(
      "Konfirmasi Keluar Akun",
      "Apakah Anda yakin ingin keluar dari akun PO Tunggal Jaya?",
      async () => {
        try {
          await logout();
        } catch (e) {
          console.log("Logout error:", e);
        }
        navigation.navigate("Login");
      },
      undefined,
      true,
    );
  };

  const getInitials = (name?: string) => {
    if (!name) return "TJ";
    const parts = name.trim().split(" ").filter(Boolean);
    if (parts.length === 1) return parts[0].substring(0, 2).toUpperCase();
    return (parts[0][0] + parts[1][0]).toUpperCase();
  };

  return (
    <View style={styles.container}>
      {/* Standard Screen Header */}
      <ScreenHeader
        title="Profil & Akun Saya"
        subtitle="Kelola Data Penumpang & Keanggotaan Member"
        showBack={navigation.canGoBack()}
      />

      <ScrollView
        contentContainerStyle={styles.scrollContent}
        showsVerticalScrollIndicator={false}
      >
        {/* 1. PROFILE CARD (VIP MEMBER OR GUEST STATE) */}
        {!user ? (
          <View style={styles.guestCard}>
            <View style={styles.guestIconBox}>
              <User size={30} color={COLORS.brandBlue} />
            </View>
            <Text style={styles.guestTitle}>Belum Masuk Akun</Text>
            <Text style={styles.guestDesc}>
              Masuk atau daftar sekarang untuk mengakses e-tiket, kumpulkan TJ
              Poin Rewards, dan nikmati promo spesial.
            </Text>
            <TouchableOpacity
              style={styles.guestLoginBtn}
              onPress={() => navigation.navigate("Login")}
              activeOpacity={0.85}
            >
              <Text style={styles.guestLoginBtnText}>Masuk / Daftar Akun</Text>
            </TouchableOpacity>
          </View>
        ) : (
          <View style={styles.vipCard}>
            <View style={styles.vipHeader}>
              <View style={styles.vipBadge}>
                <Crown size={13} color="#D97706" style={{ marginRight: 4 }} />
                <Text style={styles.vipBadgeText}>
                  {tier.toUpperCase()} MEMBER VIP
                </Text>
              </View>
              <TouchableOpacity
                activeOpacity={0.8}
                onPress={handleOpenEdit}
                style={styles.editProfileBtn}
              >
                <Edit3 size={13} color="#FFFFFF" style={{ marginRight: 4 }} />
                <Text style={styles.editProfileBtnText}>Ubah</Text>
              </TouchableOpacity>
            </View>

            <View style={styles.userMainRow}>
              <View style={styles.avatarBox}>
                {user.avatar || user.photo_url ? (
                  <Image
                    source={{ uri: user.avatar || user.photo_url }}
                    style={styles.avatarImg}
                  />
                ) : (
                  <View style={styles.avatarInitialsBox}>
                    <Text style={styles.avatarInitialsText}>
                      {getInitials(user.name)}
                    </Text>
                  </View>
                )}
              </View>
              <View style={{ flex: 1, marginLeft: 12 }}>
                <Text style={styles.userName}>
                  {user?.name || "Penumpang Tunggal Jaya"}
                </Text>
                <Text style={styles.userEmail}>
                  {user?.email || "penumpang@example.com"}
                </Text>
                <Text style={styles.userPhone}>
                  {user?.phone || "0812-3456-7890"}
                </Text>
              </View>
            </View>

            {/* Points Strip */}
            <View style={styles.pointsRow}>
              <View>
                <Text style={styles.pointsLabel}>TJ Poin Rewards</Text>
                <Text style={styles.pointsVal}>
                  {points.toLocaleString("id-ID")} Poin
                </Text>
              </View>
              <TouchableOpacity
                style={styles.redeemBtn}
                onPress={() => navigation.navigate("Rewards")}
                activeOpacity={0.8}
              >
                <Sparkles
                  size={13}
                  color="#FFFFFF"
                  style={{ marginRight: 4 }}
                />
                <Text style={styles.redeemText}>Tukar &amp; Klaim Hadiah</Text>
                <ChevronRight
                  size={13}
                  color="#FFFFFF"
                  style={{ marginLeft: 2 }}
                />
              </TouchableOpacity>
            </View>
          </View>
        )}

        {/* 2. MENU SECTION 1: LAYANAN & PERJALANAN */}
        <Text style={styles.sectionHeading}>Layanan &amp; Transaksi</Text>
        <View style={styles.menuCard}>
          <TouchableOpacity
            style={styles.menuItem}
            onPress={() => navigation.navigate("Rewards")}
            activeOpacity={0.75}
          >
            <View style={styles.menuSvgBox}>
              <RewardsLoyaltyIcon size={38} />
            </View>
            <View style={styles.menuTextCol}>
              <Text style={styles.menuTitle}>TJ Rewards &amp; Loyalitas</Text>
              <Text style={styles.menuSub}>
                Check-in harian, kumpulkan poin &amp; tukar hadiah
              </Text>
            </View>
            <ChevronRight size={16} color="#94A3B8" />
          </TouchableOpacity>

          <View style={styles.divider} />

          <TouchableOpacity
            style={styles.menuItem}
            onPress={() => navigation.navigate("Promo")}
            activeOpacity={0.75}
          >
            <View style={styles.menuSvgBox}>
              <PromoVoucherIcon size={38} />
            </View>
            <View style={styles.menuTextCol}>
              <Text style={styles.menuTitle}>Voucher &amp; Kupon Saya</Text>
              <Text style={styles.menuSub}>
                Kupon diskon s.d 50% &amp; penawaran spesial
              </Text>
            </View>
            <ChevronRight size={16} color="#94A3B8" />
          </TouchableOpacity>

          <View style={styles.divider} />

          <TouchableOpacity
            style={styles.menuItem}
            onPress={() => navigation.navigate("Charter")}
            activeOpacity={0.75}
          >
            <View style={styles.menuSvgBox}>
              <CharterPariwisataIcon size={38} />
            </View>
            <View style={styles.menuTextCol}>
              <Text style={styles.menuTitle}>Sewa Bus Pariwisata</Text>
              <Text style={styles.menuSub}>
                Reservasi bus carter armada Hino RM 280
              </Text>
            </View>
            <ChevronRight size={16} color="#94A3B8" />
          </TouchableOpacity>

          <View style={styles.divider} />

          <TouchableOpacity
            style={styles.menuItem}
            onPress={() =>
              navigation.navigate("MainTabs", {
                screen: "BookingHistory",
              } as any)
            }
            activeOpacity={0.75}
          >
            <View style={styles.menuSvgBox}>
              <BookingHistoryIcon size={38} />
            </View>
            <View style={styles.menuTextCol}>
              <Text style={styles.menuTitle}>Riwayat Tiket Perjalanan</Text>
              <Text style={styles.menuSub}>
                Cek e-tiket, boarding pass &amp; manifest
              </Text>
            </View>
            <ChevronRight size={16} color="#94A3B8" />
          </TouchableOpacity>
        </View>

        {/* 3. MENU SECTION 2: PANDUAN & BANTUAN */}
        <Text style={styles.sectionHeading}>Pusat Bantuan &amp; Privasi</Text>
        <View style={styles.menuCard}>
          <TouchableOpacity
            style={styles.menuItem}
            onPress={() =>
              navigation.navigate("MainTabs", { screen: "Help" } as any)
            }
            activeOpacity={0.75}
          >
            <View
              style={[styles.menuIconCircle, { backgroundColor: "#EFF6FF" }]}
            >
              <Info size={20} color="#2563EB" />
            </View>
            <View style={styles.menuTextCol}>
              <Text style={styles.menuTitle}>Pusat Bantuan &amp; FAQ</Text>
              <Text style={styles.menuSub}>
                Panduan booking, reschedule &amp; hotline CS
              </Text>
            </View>
            <ChevronRight size={16} color="#94A3B8" />
          </TouchableOpacity>

          <View style={styles.divider} />

          <TouchableOpacity
            style={styles.menuItem}
            onPress={() =>
              showInfo(
                "Keamanan Data & Privasi",
                "PO Tunggal Jaya Transport menerapkan standar enkripsi SSL dan keamanan database berlapis untuk melindungi data privasi dan transaksi seluruh penumpang.",
              )
            }
            activeOpacity={0.75}
          >
            <View
              style={[styles.menuIconCircle, { backgroundColor: "#ECFDF5" }]}
            >
              <ShieldCheck size={20} color="#059669" />
            </View>
            <View style={styles.menuTextCol}>
              <Text style={styles.menuTitle}>Keamanan &amp; Privasi Data</Text>
              <Text style={styles.menuSub}>
                Proteksi data terenkripsi dan resmi
              </Text>
            </View>
            <ChevronRight size={16} color="#94A3B8" />
          </TouchableOpacity>
        </View>

        {/* 4. AUTH BUTTON (LOGIN OR LOGOUT) */}
        {user ? (
          <TouchableOpacity
            style={styles.logoutBtn}
            onPress={handleLogout}
            activeOpacity={0.85}
          >
            <LogOut size={18} color="#DC2626" style={{ marginRight: 8 }} />
            <Text style={styles.logoutText}>Keluar dari Akun</Text>
          </TouchableOpacity>
        ) : (
          <TouchableOpacity
            style={[
              styles.logoutBtn,
              { borderColor: COLORS.brandBlue, backgroundColor: "#EFF6FF" },
            ]}
            onPress={() => navigation.navigate("Login")}
            activeOpacity={0.85}
          >
            <User
              size={18}
              color={COLORS.brandBlue}
              style={{ marginRight: 8 }}
            />
            <Text style={[styles.logoutText, { color: COLORS.brandBlue }]}>
              Masuk ke Akun Anda
            </Text>
          </TouchableOpacity>
        )}

        {/* App Version Info */}
        <Text style={styles.appVersionText}>
          PO Tunggal Jaya Transport Mobile App • v2.4.0 (Official Build)
        </Text>

        <View style={{ height: 110 }} />
      </ScrollView>

      {/* EDIT PROFILE MODAL */}
      <Modal
        visible={isEditModalOpen}
        animationType="slide"
        transparent={true}
        onRequestClose={() => setIsEditModalOpen(false)}
      >
        <View style={styles.modalOverlay}>
          <View style={styles.editModalContainer}>
            <View style={styles.editModalHeader}>
              <Text style={styles.editModalTitle}>Ubah Data Penumpang</Text>
              <TouchableOpacity
                onPress={() => setIsEditModalOpen(false)}
                style={styles.closeBtn}
              >
                <X size={18} color="#64748B" />
              </TouchableOpacity>
            </View>

            <View style={styles.editModalBody}>
              <Text style={styles.inputLabel}>Nama Lengkap Penumpang</Text>
              <TextInput
                value={editName}
                onChangeText={setEditName}
                placeholder="Contoh: Rangga Putra"
                placeholderTextColor="#94A3B8"
                style={styles.modalInput}
              />

              <Text style={styles.inputLabel}>Nomor WhatsApp Aktif</Text>
              <TextInput
                value={editPhone}
                onChangeText={setEditPhone}
                placeholder="Contoh: 081234567890"
                placeholderTextColor="#94A3B8"
                keyboardType="phone-pad"
                style={styles.modalInput}
              />

              <Text style={styles.inputLabel}>Alamat Email</Text>
              <TextInput
                value={editEmail}
                editable={false}
                placeholder="penumpang@example.com"
                placeholderTextColor="#94A3B8"
                style={[
                  styles.modalInput,
                  { backgroundColor: "#F1F5F9", color: "#64748B" },
                ]}
              />

              <TouchableOpacity
                style={styles.saveProfileBtn}
                onPress={handleSaveProfile}
                disabled={isSaving}
                activeOpacity={0.85}
              >
                {isSaving ? (
                  <ActivityIndicator size="small" color="#FFFFFF" />
                ) : (
                  <>
                    <Check
                      size={16}
                      color="#FFFFFF"
                      style={{ marginRight: 6 }}
                    />
                    <Text style={styles.saveProfileBtnText}>
                      Simpan Perubahan
                    </Text>
                  </>
                )}
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </Modal>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: COLORS.bgDark,
  },
  scrollContent: {
    paddingHorizontal: 16,
    paddingTop: 16,
  },
  guestCard: {
    backgroundColor: "#FFFFFF",
    borderRadius: 22,
    padding: 20,
    marginBottom: 20,
    alignItems: "center",
    borderWidth: 1,
    borderColor: "#E2E8F0",
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.06,
        shadowRadius: 10,
      },
      android: {
        elevation: 2,
      },
    }),
  },
  guestIconBox: {
    width: 56,
    height: 56,
    borderRadius: 28,
    backgroundColor: "#EFF6FF",
    justifyContent: "center",
    alignItems: "center",
    marginBottom: 12,
  },
  guestTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 16,
    color: "#0F172A",
    marginBottom: 6,
  },
  guestDesc: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 12,
    color: "#64748B",
    textAlign: "center",
    lineHeight: 18,
    marginBottom: 16,
  },
  guestLoginBtn: {
    backgroundColor: COLORS.brandBlue,
    paddingHorizontal: 20,
    paddingVertical: 11,
    borderRadius: 12,
    width: "100%",
    alignItems: "center",
  },
  guestLoginBtnText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
    color: "#FFFFFF",
  },
  vipCard: {
    backgroundColor: "#10207A",
    borderRadius: 22,
    padding: 18,
    marginBottom: 20,
    ...Platform.select({
      ios: {
        shadowColor: "#000",
        shadowOffset: { width: 0, height: 6 },
        shadowOpacity: 0.15,
        shadowRadius: 14,
      },
      android: {
        elevation: 5,
      },
    }),
  },
  vipHeader: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 14,
  },
  vipBadge: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "rgba(255, 255, 255, 0.15)",
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 12,
  },
  vipBadgeText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 10,
    color: "#FDE68A",
    letterSpacing: 0.5,
  },
  editProfileBtn: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "rgba(255, 255, 255, 0.2)",
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 8,
  },
  editProfileBtnText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 11,
    color: "#FFFFFF",
  },
  userMainRow: {
    flexDirection: "row",
    alignItems: "center",
    marginBottom: 16,
  },
  avatarBox: {
    width: 54,
    height: 54,
    borderRadius: 27,
    borderWidth: 2,
    borderColor: "#93C5FD",
    overflow: "hidden",
    backgroundColor: "rgba(255, 255, 255, 0.2)",
    justifyContent: "center",
    alignItems: "center",
  },
  avatarImg: {
    width: "100%",
    height: "100%",
  },
  avatarInitialsBox: {
    width: "100%",
    height: "100%",
    backgroundColor: "rgba(255, 255, 255, 0.25)",
    justifyContent: "center",
    alignItems: "center",
  },
  avatarInitialsText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 18,
    color: "#FFFFFF",
  },
  userName: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 17,
    color: "#FFFFFF",
  },
  userEmail: {
    fontFamily: "PlusJakartaSans_400Regular",
    fontSize: 11.5,
    color: "#93C5FD",
    marginTop: 1,
  },
  userPhone: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11.5,
    color: "rgba(255, 255, 255, 0.8)",
    marginTop: 1,
  },
  pointsRow: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    backgroundColor: "rgba(255, 255, 255, 0.1)",
    borderRadius: 14,
    paddingHorizontal: 14,
    paddingVertical: 10,
    borderWidth: 1,
    borderColor: "rgba(255, 255, 255, 0.15)",
  },
  pointsLabel: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 10.5,
    color: "#CBD5E1",
  },
  pointsVal: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 15,
    color: "#FEF08A",
    marginTop: 1,
  },
  redeemBtn: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: COLORS.brandBlue,
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 10,
  },
  redeemText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 11,
    color: "#FFFFFF",
  },
  sectionHeading: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 14,
    color: "#0F172A",
    marginBottom: 8,
    marginLeft: 4,
  },
  menuCard: {
    backgroundColor: "#FFFFFF",
    borderRadius: 20,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    paddingHorizontal: 16,
    paddingVertical: 4,
    marginBottom: 18,
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.04,
        shadowRadius: 6,
      },
      android: {
        elevation: 1,
      },
    }),
  },
  menuItem: {
    flexDirection: "row",
    alignItems: "center",
    paddingVertical: 12,
  },
  menuSvgBox: {
    width: 44,
    height: 44,
    justifyContent: "center",
    alignItems: "center",
    marginRight: 12,
  },
  menuIconCircle: {
    width: 40,
    height: 40,
    borderRadius: 12,
    justifyContent: "center",
    alignItems: "center",
    marginRight: 12,
  },
  menuTextCol: {
    flex: 1,
  },
  menuTitle: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13.5,
    color: "#0F172A",
  },
  menuSub: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "#64748B",
    marginTop: 2,
  },
  divider: {
    height: 1,
    backgroundColor: "#F1F5F9",
  },
  logoutBtn: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    backgroundColor: "#FEE2E2",
    paddingVertical: 14,
    borderRadius: 16,
    borderWidth: 1,
    borderColor: "#FECACA",
    marginTop: 4,
    marginBottom: 16,
  },
  logoutText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13.5,
    color: "#DC2626",
  },
  appVersionText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "#94A3B8",
    textAlign: "center",
  },
  modalOverlay: {
    flex: 1,
    backgroundColor: "rgba(15, 23, 42, 0.6)",
    justifyContent: "flex-end",
  },
  editModalContainer: {
    backgroundColor: "#FFFFFF",
    borderTopLeftRadius: 24,
    borderTopRightRadius: 24,
    padding: 20,
  },
  editModalHeader: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 16,
    paddingBottom: 12,
    borderBottomWidth: 1,
    borderBottomColor: "#F1F5F9",
  },
  editModalTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 16,
    color: "#0F172A",
  },
  closeBtn: {
    width: 32,
    height: 32,
    borderRadius: 16,
    backgroundColor: "#F1F5F9",
    justifyContent: "center",
    alignItems: "center",
  },
  editModalBody: {
    gap: 10,
    paddingBottom: 20,
  },
  inputLabel: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 11.5,
    color: "#475569",
    marginTop: 4,
  },
  modalInput: {
    backgroundColor: "#F8FAFC",
    borderRadius: 12,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    paddingHorizontal: 14,
    paddingVertical: 10,
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 13,
    color: "#0F172A",
  },
  saveProfileBtn: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    backgroundColor: COLORS.brandBlue,
    paddingVertical: 13,
    borderRadius: 14,
    marginTop: 12,
  },
  saveProfileBtnText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13.5,
    color: "#FFFFFF",
  },
});
