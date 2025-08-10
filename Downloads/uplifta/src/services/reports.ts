export type SeriesPoint = { month: string; value: number };

const months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];

export async function fetchDisbursementSeries(): Promise<SeriesPoint[]> {
  await new Promise((r) => setTimeout(r, 300));
  return months.map((m, i) => ({ month: m, value: 5 + Math.round(Math.sin(i) * 3 + i) }));
}
export async function fetchRepaymentSeries(): Promise<SeriesPoint[]> {
  await new Promise((r) => setTimeout(r, 300));
  return months.map((m, i) => ({ month: m, value: 4 + Math.round(Math.cos(i) * 2 + i) }));
}
export async function fetchCashflowSeries(): Promise<SeriesPoint[]> {
  await new Promise((r) => setTimeout(r, 300));
  return months.map((m, i) => ({ month: m, value: 2 + Math.round(Math.sin(i/2) * 2 + i/2) }));
}


