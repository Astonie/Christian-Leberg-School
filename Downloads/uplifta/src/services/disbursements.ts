import { Disbursement } from "./types";

let MOCK: Disbursement[] = Array.from({ length: 10 }).map((_, i) => ({
  id: `DB-${100 + i}`,
  clientName: `Client ${i + 1}`,
  amount: 1_000_000 + i * 250_000,
  scheduledDate: new Date(Date.now() + i * 86400000).toISOString(),
  paid: false,
}));

export async function listDisbursements(): Promise<Disbursement[]> {
  await new Promise((r) => setTimeout(r, 500));
  return MOCK;
}

export async function markDisbursementPaid(id: string): Promise<void> {
  await new Promise((r) => setTimeout(r, 400));
  MOCK = MOCK.map((d) => (d.id === id ? { ...d, paid: true } : d));
}


