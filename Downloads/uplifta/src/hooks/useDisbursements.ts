"use client";

import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { listDisbursements, markDisbursementPaid } from "@/services/disbursements";

export function useDisbursements() {
  return useQuery({ queryKey: ["disbursements"], queryFn: listDisbursements });
}

export function useMarkDisbursementPaid() {
  const qc = useQueryClient();
  return useMutation({
    mutationFn: ({ id }: { id: string; reference: string }) => markDisbursementPaid(id),
    onSuccess: () => qc.invalidateQueries({ queryKey: ["disbursements"] }),
  });
}


