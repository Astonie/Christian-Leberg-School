"use client";

import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { listLoanProducts, createLoanProduct, updateLoanProduct, deleteLoanProduct } from "@/services/products";

export function useLoanProducts() {
  return useQuery({ queryKey: ["loanProducts"], queryFn: listLoanProducts });
}

export function useCreateLoanProduct() {
  const qc = useQueryClient();
  return useMutation({ mutationFn: createLoanProduct, onSuccess: () => qc.invalidateQueries({ queryKey: ["loanProducts"] }) });
}
export function useUpdateLoanProduct() {
  const qc = useQueryClient();
  return useMutation({ mutationFn: ({ id, input }: { id: string; input: Parameters<typeof updateLoanProduct>[1] }) => updateLoanProduct(id, input), onSuccess: () => qc.invalidateQueries({ queryKey: ["loanProducts"] }) });
}
export function useDeleteLoanProduct() {
  const qc = useQueryClient();
  return useMutation({ mutationFn: deleteLoanProduct, onSuccess: () => qc.invalidateQueries({ queryKey: ["loanProducts"] }) });
}


