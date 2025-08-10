export type Client = {
  id: string;
  name: string;
  phone: string;
  email?: string;
  createdAt: string;
  loansCount: number;
};

export type Loan = {
  id: string;
  clientName: string;
  principal: number;
  balance: number;
  nextDueDate?: string;
  status: "active" | "closed" | "delinquent";
};

export type Disbursement = {
  id: string;
  clientName: string;
  amount: number;
  scheduledDate: string;
  paid: boolean;
};

export type Repayment = {
  id: string;
  loanId: string;
  clientName: string;
  amount: number;
  paidAt: string;
  reference?: string;
};


