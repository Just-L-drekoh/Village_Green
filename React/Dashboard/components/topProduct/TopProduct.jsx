import React, {useState, useEffect} from 'react'



const TopProduct =() =>{

    const [topProduct, setTopProduct] = useState([])
    const [loading, setLoading] =useState(true)
    const [error, setError] = useState(null)
    const [year, setYear] = useState(new Date().getFullYear())


    useEffect(()=>{
        const fetchTopProduct = async ()=>{
            setLoading(true)
        try {
            const response = await fetch (`/api/dashboard/topProduct?q=${year}`)
            if (!response.ok) {
                throw new Error("Une errur est survrnue peandant la récuperation des données ")
            }
            const data = await response.json()
            setTopProduct(data.data);
            console.log(data)
            setError(null)
        }catch(err)
        {
            console.log("Une erreur Api est survenue :", err)
            setError(err.message)
            setTopProduct([])

        } finally {
            setLoading(false);
        }
        }

        fetchTopProduct()
        console.log("topProduct avant le map:", topProduct);

    },[year])

    return (
        <div className="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-lg">
          <h2 className="text-2xl font-bold mb-4 text-gray-800">
            Top Produits - {year}
          </h2>
    
          <div className="mb-4">
            <label className="text-gray-700 font-semibold mr-2">Année :</label>
            <select
              value={year}
              onChange={(e) => setYear(parseInt(e.target.value))}
              className="p-2 border rounded-md"
            >
              {Array.from({ length: 5 }, (_, i) => new Date().getFullYear() - i).map(
                (y) => (
                  <option key={y} value={y}>
                    {y}
                  </option>
                )
              )}
            </select>
          </div>
    
          {error && <p className="text-red-500">{error}</p>}
    
          {loading && <p className="text-gray-500">Chargement...</p>}
    
          {!loading && !error && (
            <div className="overflow-x-auto">
              <table className="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead>
                  <tr className="bg-gray-100">
                    <th className="py-2 px-4 border">ID</th>
                    <th className="py-2 px-4 border">Référence</th>
                    <th className="py-2 px-4 border">Fournisseurs</th>
                    <th className="py-2 px-4 border">Total Produits (€)</th>
                    <th className="py-2 px-4 border">Total Commande (€)</th>
                    <th className="py-2 px-4 border">Marge (€)</th>
                  </tr>
                </thead>
                <tbody>
                  {topProduct.map((item) => (
                    <tr key={item.Commande_ID} className="border-t">
                      <td className="py-2 px-4 border text-center">
                        {item.Commande_ID}
                      </td>
                      <td className="py-2 px-4 border">{item.Reference_Commande}</td>
                      <td className="py-2 px-4 border">{item.Fournisseurs}</td>
                      <td className="py-2 px-4 border text-right">
                        {item.Total_Produits} €
                      </td>
                      <td className="py-2 px-4 border text-right">
                        {item.Total_Commande} €
                      </td><td className="py-2 px-4 border text-right">
                        {item.Marge} €
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          )}
        </div>
      );
}

export default TopProduct