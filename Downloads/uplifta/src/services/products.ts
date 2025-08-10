export type LoanProduct = {
  id: string;
  name: string;
  interestRateAnnualPct: number;
  maxAmount: number;
  minTermMonths: number;
  maxTermMonths: number;
};

let MOCK_PRODUCTS: LoanProduct[] = [
  { id: "prd-1", name: "Micro Loan", interestRateAnnualPct: 24, maxAmount: 5_000_000, minTermMonths: 3, maxTermMonths: 12 },
  { id: "prd-2", name: "SME Loan", interestRateAnnualPct: 20, maxAmount: 50_000_000, minTermMonths: 6, maxTermMonths: 24 },
];

export async function listLoanProducts(): Promise<LoanProduct[]> {
  await new Promise((r) => setTimeout(r, 400));
  return MOCK_PRODUCTS;
}

export async function createLoanProduct(input: Omit<LoanProduct, "id">): Promise<LoanProduct> {
  await new Promise((r) => setTimeout(r, 300));
  const id = `prd-${Math.random().toString(36).slice(2, 8)}`;
  const product: LoanProduct = { id, ...input };
  MOCK_PRODUCTS = [product, ...MOCK_PRODUCTS];
  return product;
}

export async function updateLoanProduct(id: string, input: Omit<LoanProduct, "id">): Promise<LoanProduct> {
  await new Promise((r) => setTimeout(r, 300));
  let updated: LoanProduct | undefined;
  MOCK_PRODUCTS = MOCK_PRODUCTS.map((p) => (p.id === id ? (updated = { id, ...input })! : p));
  return updated ?? { id, ...input };
}

export async function deleteLoanProduct(id: string): Promise<void> {
  await new Promise((r) => setTimeout(r, 200));
  MOCK_PRODUCTS = MOCK_PRODUCTS.filter((p) => p.id !== id);
}


