import { Loan } from "./types";

const MOCK_LOANS: Loan[] = Array.from({ length: 30 }).map((_, i) => ({
  id: `L-2024-${(i + 1).toString().padStart(3, "0")}`,
  clientName: `Client ${i + 1}`,
  principal: 2_000_000 + i * 100_000,
  balance: 1_500_000 - i * 50_000,
  nextDueDate: new Date(Date.now() + (i % 12) * 86400000).toISOString(),
  status: i % 7 === 0 ? "delinquent" : "active",
}));

export async function listLoans(): Promise<Loan[]> {
  await new Promise((r) => setTimeout(r, 600));
  return MOCK_LOANS;
}


