export const MONTH_NAMES_ID = [
  "Januari",
  "Februari",
  "Maret",
  "April",
  "Mei",
  "Juni",
  "Juli",
  "Agustus",
  "September",
  "Oktober",
  "November",
  "Desember",
];

export const MONTH_SHORT_ID = [
  "Jan",
  "Feb",
  "Mar",
  "Apr",
  "Mei",
  "Jun",
  "Jul",
  "Agu",
  "Sep",
  "Okt",
  "Nov",
  "Des",
];

/**
 * Formats an ISO string or YYYY-MM-DD to human readable Indonesian date
 * Example: '2026-09-08' -> '8 September 2026' or '8 Sep 2026'
 */
export function formatIndonesianDate(
  dateStr: string | null | undefined,
  shortMonth = true,
): string {
  if (!dateStr) return "-";

  // Extract YYYY-MM-DD from string if available
  const cleanStr = String(dateStr).substring(0, 10);
  const parts = cleanStr.split("-");

  if (parts.length === 3) {
    const year = parts[0];
    const monthIndex = parseInt(parts[1], 10) - 1;
    const day = parseInt(parts[2], 10);
    const months = shortMonth ? MONTH_SHORT_ID : MONTH_NAMES_ID;
    const monthName = months[monthIndex] || parts[1];
    return `${day} ${monthName} ${year}`;
  }

  const d = new Date(dateStr);
  if (isNaN(d.getTime())) return String(dateStr);

  const months = shortMonth ? MONTH_SHORT_ID : MONTH_NAMES_ID;
  return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
}

/**
 * Formats a start and end date range cleanly with month names
 * Example: '2026-09-08' and '2026-09-11' -> '8 - 11 Sep 2026'
 * Example: '2026-09-28' and '2026-10-02' -> '28 Sep - 2 Okt 2026'
 */
export function formatCharterDateRange(
  startDateStr: string | null | undefined,
  endDateStr: string | null | undefined,
): string {
  if (!startDateStr && !endDateStr) return "-";
  if (!endDateStr) return formatIndonesianDate(startDateStr);
  if (!startDateStr) return formatIndonesianDate(endDateStr);

  const cleanStart = String(startDateStr).substring(0, 10);
  const cleanEnd = String(endDateStr).substring(0, 10);

  const startParts = cleanStart.split("-");
  const endParts = cleanEnd.split("-");

  if (startParts.length === 3 && endParts.length === 3) {
    const [startYear, startMonth, startDay] = [
      startParts[0],
      parseInt(startParts[1], 10) - 1,
      parseInt(startParts[2], 10),
    ];
    const [endYear, endMonth, endDay] = [
      endParts[0],
      parseInt(endParts[1], 10) - 1,
      parseInt(endParts[2], 10),
    ];

    // Same year and same month: '8 - 11 Sep 2026'
    if (startYear === endYear && startMonth === endMonth) {
      return `${startDay} - ${endDay} ${MONTH_SHORT_ID[startMonth] || ""} ${startYear}`;
    }

    // Same year, different month: '28 Sep - 2 Okt 2026'
    if (startYear === endYear) {
      return `${startDay} ${MONTH_SHORT_ID[startMonth] || ""} - ${endDay} ${MONTH_SHORT_ID[endMonth] || ""} ${startYear}`;
    }

    // Different years: '28 Des 2026 - 3 Jan 2027'
    return `${startDay} ${MONTH_SHORT_ID[startMonth] || ""} ${startYear} - ${endDay} ${MONTH_SHORT_ID[endMonth] || ""} ${endYear}`;
  }

  return `${formatIndonesianDate(startDateStr)} - ${formatIndonesianDate(endDateStr)}`;
}

/**
 * Formats a departure/arrival time string or ISO UTC datetime to Indonesian Time (WIB, Asia/Jakarta)
 * Handles ISO with UTC "2000-01-01T00:00:00.000000Z" -> "07:00"
 * Handles "2000-01-01 07:00:00" -> "07:00"
 * Handles "07:00:00" -> "07:00"
 */
export function formatIndonesianTime(
  timeStr: string | null | undefined,
  fallback = "07:00",
): string {
  if (!timeStr) return fallback;

  // 1. If ISO string with Z (UTC timezone, e.g. "2000-01-01T00:00:00.000000Z")
  if (
    typeof timeStr === "string" &&
    (timeStr.includes("Z") || (timeStr.includes("T") && timeStr.endsWith("Z")))
  ) {
    const d = new Date(timeStr);
    if (!isNaN(d.getTime())) {
      // In JS, getHours() returns local browser time (which in Indonesia is UTC+7)
      // Or to be 100% deterministic for Asia/Jakarta (UTC+7):
      const utcHours = d.getUTCHours();
      const utcMinutes = d.getUTCMinutes();
      const wibHours = (utcHours + 7) % 24;
      return `${wibHours.toString().padStart(2, "0")}:${utcMinutes.toString().padStart(2, "0")}`;
    }
  }

  // 2. If standard ISO without Z "2000-01-01T07:00:00"
  if (typeof timeStr === "string" && timeStr.includes("T")) {
    const timePart = timeStr.split("T")[1];
    if (timePart && timePart.length >= 5) {
      return timePart.substring(0, 5);
    }
  }

  // 3. If MySQL/Postgres date string "2000-01-01 07:00:00"
  if (typeof timeStr === "string" && timeStr.includes(" ")) {
    const timePart = timeStr.split(" ")[1];
    if (timePart && timePart.length >= 5) {
      return timePart.substring(0, 5);
    }
  }

  // 4. If plain time "07:00:00" or "07:00"
  if (typeof timeStr === "string" && /^\d{2}:\d{2}/.test(timeStr)) {
    return timeStr.substring(0, 5);
  }

  return String(timeStr).substring(0, 5) || fallback;
}
